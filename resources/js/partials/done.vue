<template>
    <div class="done p-2 bg-warning-custom text-warning fw-bold card-list w-100">
        <div class="list-header mb-2">
            <span class="list-drag-handle">&#x2630;</span>
            Done
        </div>
        <!-- Testing draggable component. Pass done to list prop -->
        <draggable
            id="done"
            v-bind="dragOptions"
            class="done list-group kanban-column"
            :list="done"
            group="tasks"
            :move="checkMove"
        >
            <div
                class="list-group-item  text-capitalize"
                v-for="element in doneList"
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
                        class="list-group-staff staff-list-stack"
                        v-for="staf in element.staffs"
                        :key="staf.id"
                    >
                        <div
                            class="avatar mt-2 me-2"
                            data-bs-toggle="tooltip"
                            :title="staf.nama"
                        >
                            <img height="25" width="25" :src="staf.foto" alt="" @error="onErrorImg"/>
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
        done: Array,
    },
    data(){
        return{
            doneList : [],
        }
    },
    computed: {
        dragOptions() {
            return {
                animation: 800,
                disabled:   true,
                ghostClass: "ghost",
            };
        },
        // this.value when input = v-model
        // this.list  when input != v-model
        realValue() {
            return this.value ? this.value : this.list;
        },
    },
    watch:{
        done(){
            this.doneList = this.done
            console.log(this.done);
        }
    },
    methods: {
        onErrorImg(e){
            this.$parent.onErrorImg(e);
        },
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
    .alert.alert-warning{
        background-color: #fff3cdb5;
    }
    .avatar{
        border: 2px solid rgb(202, 202, 0);
    }
    .bg-warning-custom{
        background-color: #ffc70059;
    }
</style>
