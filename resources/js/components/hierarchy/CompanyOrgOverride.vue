<template>
    <div class="override-wrapper">
        <div class="normal-wrapper clearfix" v-if="!item.override">
            <div class="name float-left" v-text="item.name"></div>
            <div class="float-left link">
                <a href="#" @click.prevent="overrideItem">Override</a>
            </div>
        </div>
        <div class="overridden-wrapper clearfix" v-else>
            <div class="float-left original" v-text="item.original"></div>
            <div class="float-left input-field">
                <input type="text" class="form-control" v-model="item.name" @change="aliasChanged" />
            </div>
            <div class="float-left link" v-if="showMoveUp">
                <a href="#" @click.prevent="moveUp">Move Up</a>
            </div>
            <div class="float-left link" v-if="showMoveDown">
                <a href="#" @click.prevent="moveDown">Move Down</a>
            </div>
            <div class="float-left link">
                <a href="#" @click.prevent="cancelOverride">Cancel</a>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "CompanyOrgOverride",
    props: ["item"],

    methods: {

        async aliasChanged() {
console.log("Changed Fired");
            const response = (await axios.post("/admin/hierarchy/companies/" + this.$parent.companyId + "/organizations/" + this.item.type + "/" + this.item.id, {
                alias: this.item.name,
            })).data;

            // console.log(response);
        },

        overrideItem() {
            this.item.override = true;
            this.item.original = this.item.name;
        },

        async cancelOverride() {
            const response = (await axios.delete("/admin/hierarchy/companies/" + this.$parent.companyId + "/organizations/" + this.item.type + "/" + this.item.id)).data;

            // console.log(response);

            this.item.name = this.item.original;
            this.item.original = null;
            this.item.override = false;
        },

        moveUp() {
            this.moveToIdx(this.parentIdx - 1);
        },

        moveDown() {
            this.moveToIdx(this.parentIdx + 1);
        },

        async moveToIdx(newIdx) {
console.log("Move Clicked");
            const parentIdx = this.parentIdx;

            const currentIdx = this.$parent.organizations[parentIdx].sub_departments.findIndex(s => {
                return s.id === this.item.id;
            });

            const newId = this.$parent.organizations[newIdx].id;

            const response = (await axios.post("/admin/hierarchy/companies/" + this.$parent.companyId + "/organizations/" + this.item.type + "/" + this.item.id + "/move", {
                newId: newId,
            })).data;

            this.$parent.organizations[newIdx].sub_departments.push(this.item);
            this.$parent.organizations[parentIdx].sub_departments.splice(currentIdx, 1);
        }

    },

    computed: {
        parentIdx() {
            if(this.item.type !== "sub_department") return -1;

            return this.$parent.organizations.findIndex(d => {
                return (-1 < d.sub_departments.findIndex(s => {
                    return s.id === this.item.id;
                }));
            });
        },

        showMoveUp() {
            return this.item.type === "sub_department" && 0 !== this.parentIdx;
        },

        showMoveDown() {
            return this.item.type === "sub_department" && (this.$parent.organizations.length -1) > this.parentIdx;
        }
    }
}
</script>

<style scoped>

    .override-wrapper .name,
    .overridden-wrapper .original {
        margin-right: 20px;
    }

    .overridden-wrapper .input-field {
        margin-right: 20px;
    }

    .overridden-wrapper .link {
        margin-left: 10px;
    }

    .overridden-wrapper .original {
        text-decoration: line-through;
    }
</style>
