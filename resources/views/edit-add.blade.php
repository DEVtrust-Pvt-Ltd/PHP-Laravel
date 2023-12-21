@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                    
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ str_replace('_',' ',ucfirst($row->getTranslatedAttribute('display_name'))) }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if ($add && isset($row->details->view_add))
                                        @include($row->details->view_add, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'add', 'options' => $row->details])
                                    @elseif ($edit && isset($row->details->view_edit))
                                        @include($row->details->view_edit, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'edit', 'options' => $row->details])
                                    @elseif (isset($row->details->view))
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                    @elseif ($row->type == 'relationship')
                                       
                                        @include('voyager::formfields.relationshipmodify', ['options' => $row->details])
                                        
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->display_name)) 
                                        @foreach ($errors->get($row->display_name) as $error)
                                            <span class="help-block" style="color: #a94442;">{{  str_replace('_',' ',ucfirst($error)) }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                    <div style="display:none">
                        <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                        <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop


@section('javascript')

<script>
   const elementsByName = document.getElementsByName("Effort");
    if (elementsByName.length > 0) {
        // Access the first element with the specified name
        const element = elementsByName[0];
        document.getElementsByName('Effort')[0].placeholder='HH:MM:SS';
        // Set the oninput attribute to "autoInsertColon(this)"
        element.setAttribute('oninput', 'autoInsertColon(this)');
    } else {
        console.log('No elements with the specified name found.');
    }
        function autoInsertColon(inputField) {
            // Remove any characters that are not numbers
            const cleanedValue = inputField.value.replace(/[^0-9]/g, '');

            // Split the cleaned value into parts (hours, minutes, seconds)
            const hours = cleanedValue.slice(0, 2);
            const minutes = cleanedValue.slice(2, 4);
            const seconds = cleanedValue.slice(4, 6);

            // Limit minutes and seconds to a maximum of 59
            let adjustedMinutes = parseInt(minutes) > 59 ? '59' : minutes;
            let adjustedSeconds = parseInt(seconds) > 59 ? '59' : seconds;

            // Build the formatted time with colons
            let formattedTime = '';
            if (hours) {
                formattedTime += hours;
            }
            if (minutes) {
                formattedTime += ':' + adjustedMinutes;
            }
            if (seconds) {
                formattedTime += ':' + adjustedSeconds;
            }

            // Update the input field with the formatted time
            inputField.value = formattedTime;
        }
    </script>

    <script>
        const target_time = document.getElementsByName("Targeted_Complete_Time"); 
        const target_date_time = target_time[0];
         target_date_time.setAttribute('id', 'completionTime')
         
         const target_date = document.getElementsByName("Targeted_Complete_Date"); 
         const target= target_date[0];
         target.setAttribute('id', 'completionDate');

         const effort_time = document.getElementsByName("Effort"); 
         const effort= effort_time[0];
         effort.setAttribute('id', 'effort');
         
         const Projected = document.getElementsByName("Projected_Start_Date_&_Time"); 
         const Projected_result= Projected[0];
         Projected_result.setAttribute('id', 'result');
         Projected_result.setAttribute('readonly', 'readonly');

         const completionDateInput = document.getElementById("completionDate");
        const completionTimeInput = document.getElementById("completionTime");
        const effortInput = document.getElementById("effort");
        const resultInput = document.getElementById("result");
       
        function calculateResult() {
            const completionDateValue = completionDateInput.valueAsDate;
            const completionTimeValue = completionTimeInput.valueAsDate;
            const effortText = effortInput.value;

            if (completionDateValue && completionTimeValue) {
                // Combine the date and time values
                const completionDateTime = new Date(completionDateValue);
                completionDateTime.setHours(completionTimeValue.getHours(), completionTimeValue.getMinutes());

                // Parse the effort input in HH:MM:SS format
                const [effortHours, effortMinutes, effortSeconds] = effortText.split(":");
                const effortHoursNum = parseInt(effortHours, 10) || 0;
                const effortMinutesNum = parseInt(effortMinutes, 10) || 0;
                const effortSecondsNum = parseInt(effortSeconds, 10) || 0;

                // Subtract the effort (in hours)
                completionDateTime.setHours(completionDateTime.getHours() - effortHoursNum);
                completionDateTime.setMinutes(completionDateTime.getMinutes() - effortMinutesNum);
                completionDateTime.setSeconds(completionDateTime.getSeconds() - effortSecondsNum);

                // Display the result
                
                const resultDate = completionDateTime.toISOString().slice(0, 19).replace("T", " ");
                resultInput.value = `${resultDate}`;
                resultInput.setAttribute('value', `${resultDate}`);
            } else {
                resultInput.value = "";
            }
        }

        // Add event listeners to trigger the calculation when inputs change
        completionDateInput.addEventListener("change", calculateResult);
        completionTimeInput.addEventListener("change", calculateResult);
        effortInput.addEventListener("input", calculateResult);
    </script>
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop