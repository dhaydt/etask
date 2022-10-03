<template>
    <div class="p-2 alert alert-success">
        <div class="list-header mb-2">
            <span class="list-drag-handle">&#x2630;</span>
            Staff
        </div>
        <!-- Done draggable component. Pass staff to list prop -->
        <draggable
            v-bind="dragOptions"
            class="list-group kanban-column"
            :list="staffs"
            group="task-staff"
            :move="checkStaff"
        >
            <div
                class="list-group-staff d-flex card flex-row shadow-sm list-group-item text-capitalize"
                v-for="element in staffs"
                :key="element.name"
                data-bs-toggle="tooltip"
                :title="element.name"
            >
                <div class="avatar me-2 text-capitalize position-relative">
                    <img height="25" src="img/user.png" alt="" />
                </div>
                <div class="staff-name d-flex justify-content-center">
                    <div class="staff-label">
                        {{ element.name }}
                    </div>
                </div>
            </div>
        </draggable>
    </div>
</template>

<script>
export default {
    props: {
        staffs: Array,
    },
    computed: {
        dragOptions() {
            return {
                animation: 800,
                disabled: false,
                ghostClass: "ghost",
            };
        },
        // this.value when input = v-model
        // this.list  when input != v-model
        realValue() {
            return this.value ? this.value : this.list;
        },
    },

    methods: {
        checkStaff(evt) {
            this.$parent.checkStaff(evt);
        },
    },
};
</script>

<style lang="scss" scoped>
.alert.alert-success {
    background-color: #d1e7ddab;
}
.staff-name {
    overflow: hidden;
}
.staff-label {
    font-size: 12px;
    font-weight: 600;
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
}
</style>
