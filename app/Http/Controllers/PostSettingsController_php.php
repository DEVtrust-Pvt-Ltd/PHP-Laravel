<?php

namespace App\Http\Controllers\post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TCG\post\Facades\post;
use Exception;

class PostSettingsController extends Controller
{   

    /**
     * Display the settings index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Check permission
        $this->authorize('browse', post::model('Setting'));

        // Fetch settings data
        $data = post::model('Setting')->orderBy('order', 'ASC')->get();

        // Organize settings into groups
        $settings = [];
        $settings[__('post::settings.group_general')] = [];
        foreach ($data as $d) {
            if ($d->group == '' || $d->group == __('post::settings.group_general')) {
                $settings[__('post::settings.group_general')][] = $d;
            } else {
                $settings[$d->group][] = $d;
            }
        }
        
        // Remove empty general group if no settings in it
        if (count($settings[__('post::settings.group_general')]) == 0) {
            unset($settings[__('post::settings.group_general')]);
        }

        // Fetch distinct groups for dropdown
        $groups_data = post::model('Setting')->select('group')->distinct()->get();
        $groups = [];
        foreach ($groups_data as $group) {
            if ($group->group != '') {
                $groups[] = $group->group;
            }
        }

        // Determine active tab
        $active = (request()->session()->has('setting_tab')) ? request()->session()->get('setting_tab') : old('setting_tab', key($settings));

        return post::view('post::settings.index', compact('settings', 'groups', 'active'));
    }

    /**
     * Store a new setting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Check permission
        $this->authorize('add', post::model('Setting'));

        // Build unique key
        $key = implode('.', [Str::slug($request->input('group')), $request->input('key')]);
        $key_check = post::model('Setting')->where('key', $key)->get()->count();

        // Check if key already exists
        if ($key_check > 0) {
            return back()->with([
                'message'    => __('post::settings.key_already_exists', ['key' => $key]),
                'alert-type' => 'error',
            ]);
        }

        // Set order for the new setting
        $lastSetting = post::model('Setting')->orderBy('order', 'DESC')->first();
        $order = (is_null($lastSetting)) ? 0 : intval($lastSetting->order) + 1;

        // Set default values and create setting
        $request->merge(['order' => $order]);
        $request->merge(['value' => '']);
        $request->merge(['key' => $key]);
        post::model('Setting')->create($request->except('setting_tab'));

        // Flash the active tab and return with success message
        request()->flashOnly('setting_tab');
        return back()->with([
            'message'    => __('post::settings.successfully_created'),
            'alert-type' => 'success',
        ]);
    }

    /**
     * Update all settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Check permission
        $this->authorize('edit', post::model('Setting'));

        // Fetch all settings
        $settings = post::model('Setting')->all();

        // Loop through settings and update values
        foreach ($settings as $setting) {
            $content = $this->getContentBasedOnType($request, 'settings', (object) [
                'type'    => $setting->type,
                'field'   => str_replace('.', '_', $setting->key),
                'group'   => $setting->group,
            ], $setting->details);

            // Skip update if content is null for image or file type
            if (($setting->type == 'image' || $setting->type == 'file') && $content == null) {
                continue;
            }

            // Update setting values
            $key = preg_replace('/^'.Str::slug($setting->group).'./i', '', $setting->key);
            $setting->group = $request->input(str_replace('.', '_', $setting->key).'_group');
            $setting->key = implode('.', [Str::slug($setting->group), $key]);
            $setting->value = $content;
            $setting->save();
        }

        // Flash the active tab and return with success message
        request()->flashOnly('setting_tab');
        return back()->with([
            'message'    => __('post::settings.successfully_saved'),
            'alert-type' => 'success',
        ]);
    }

    /**
     * Delete a setting.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {

        // Check permission
        $this->authorize('delete', post::model('Setting'));

        // Find and delete the setting
        $setting = post::model('Setting')->find($id);
        post::model('Setting')->destroy($id);

        // Flash the active tab and return with success message
        request()->session()->flash('setting_tab', $setting->group);
        return back()->with([
            'message'    => __('post::settings.successfully_deleted'),
            'alert-type' => 'success',
        ]);
    }

    /**
     * Move a setting up in order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function move_up($id)
    {
        // Check permission
        $this->authorize('edit', post::model('Setting'));

        // Find the setting to move
        $setting = post::model('Setting')->find($id);

        // Check permission
        $this->authorize('browse', $setting);

        // Swap order with the previous setting
        $swapOrder = $setting->order;
        $previousSetting = post::model('Setting')
                            ->where('order', '<', $swapOrder)
                            ->where('group', $setting->group)
                            ->orderBy('order', 'DESC')->first();
        $data = [
            'message'    => __('post::settings.already_at_top'),
            'alert-type' => 'error',
        ];
        // Check if there is a previous setting
        if (isset($previousSetting->order)) {
            $setting->order = $previousSetting->order;
            $setting->save();
            $previousSetting->order = $swapOrder;
            $previousSetting->save();

            $data = [
                'message'    => __('post::settings.moved_order_up', ['name' => $setting->display_name]),
                'alert-type' => 'success',
            ];
        }

        // Flash the active tab and return with success or error message
        request()->session()->flash('setting_tab', $setting->group);
        return back()->with($data);
    }

    /**
     * Remove the value of a setting.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete_value($id)
    {
        // Find the setting to remove the value
        $setting = post::model('Setting')->find($id);

        // Check permission
        $this->authorize('delete', $setting);

        // Check if the setting exists
        if (isset($setting->id)) {
            // If the type is an image, delete it
            if ($setting->type == 'image') {
                if (Storage::disk(config('post.storage.disk'))->exists($setting->value)) {
                    Storage::disk(config('post.storage.disk'))->delete($setting->value);
                }
            }
            $setting->value = '';
            $setting->save();
        }
        // Flash the active tab and return with success message
        request()->session()->flash('setting_tab', $setting->group);
        return back()->with([
            'message'    => __('post::settings.successfully_removed', ['name' => $setting->display_name]),
            'alert-type' => 'success',
        ]);
    }

    /**
     * Move a setting down in order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function move_down($id)
    {
        // Check permission
        $this->authorize('edit', post::model('Setting'));

        // Find the setting to move
        $setting = post::model('Setting')->find($id);

        // Check permission
        $this->authorize('browse', $setting);

        // Swap order with the next setting
        $swapOrder = $setting->order;
        $previousSetting = post::model('Setting')
                            ->where('order', '>', $swapOrder)
                            ->where('group', $setting->group)
                            ->orderBy('order', 'ASC')->first();
        $data = [
            'message'    => __('post::settings.already_at_bottom'),
            'alert-type' => 'error',
        ];

        // Check if there is a next setting
        if (isset($previousSetting->order)) {
            $setting->order = $previousSetting->order;
            $setting->save();
            $previousSetting->order = $swapOrder;
            $previousSetting->save();

            $data = [
                'message'    => __('post::settings.moved_order_down', ['name' => $setting->display_name]),
                'alert-type' => 'success',
            ];
        }

        // Flash the active tab and return with success or error message
        request()->session()->flash('setting_tab', $setting->group);
        return back()->with($data);
    }
}
