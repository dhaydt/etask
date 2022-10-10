<template>
    <div class="p-2 bg-light-success text-success bg-hover-success card-list">
        <div class="list-header mb-2 fw-bold">
            <span class="list-drag-handle">&#x2630;</span>
            ASN Terkait
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
                class="list-group-staff mb-2 d-flex card flex-row shadow-sm list-group-item text-capitalize position-relative"
                v-for="element in staffs"
                :key="element.name"
                data-bs-toggle="tooltip"
                :title="element.name"
            >
                <label
                    v-if="element.available == 1"
                    class="badge bg-success rounded-pill staff-status position-absolute"
                    >Available</label
                >
                <label
                    v-else
                    class="badge bg-danger rounded-pill staff-status position-absolute"
                    >OnWorking</label
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
                disabled: true,
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
.staff-status {
    top: -5px;
    right: 0;
    font-size: 10px;
}
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
