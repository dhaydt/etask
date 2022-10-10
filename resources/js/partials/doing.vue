<template>
    <div class="p-2 bg-light-primary text-info fw-bold bg-hover-primary card-list">
        <div class="list-header mb-2">
            <span class="list-drag-handle">&#x2630;</span>
            Doing
        </div>
        <!-- In Progress draggable component. Pass doing to list prop -->
        <draggable
            v-bind="dragOptions"
            class="list-group kanban-column"
            id="doing"
            :list="doing"
            group="tasks"
            :move="checkMove"
        >
            <div
                class="list-group-item  text-capitalize"
                v-for="element in doing"
                :key="element.name"
                @click="cardModal(element)"
            >
                {{ element.name }}
                <draggable
                    v-bind="dragOptions"
                    class="list-staff d-flex"
                    :id="element.id"
                    :list="element.staffs"
                    group="task-staff"
                    :move="checkStaff"
                >
                    <div
                        class="list-group-staff"
                        v-for="staf in element.staffs"
                        :key="staf.id"
                    >
                        <div
                            class="avatar mt-2 me-2"
                            data-bs-toggle="tooltip"
                            :title="staf.name"
                        >
                            <img height="25" src="img/user.png" alt="" />
                        </div>
                    </div>
                </draggable>
            </div>
        </draggable>
    </div>
</template>

<script>
export default {
    props: {
        doing: Array,
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
        cardModal(data){
            this.$parent.cardModal(data);
        },
        checkMove(evt) {
            this.$parent.checkMove(evt);
        },
        checkStaff(evt) {
            this.$parent.checkStaff(evt);
        },
    },
};
</script>

<style lang="scss" scoped>
    .alert.alert-primary{
        background-color: #cfe2ffa1;
    }
</style>
