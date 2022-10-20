<template>
    <div class="p-2 bg-primary-custom fw-bold card-list w-100 doing">
        <div class="list-header mb-4">
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
                class="list-group-item overflow-hidden text-capitalize mb-4"
                v-for="element in doList"
                :key="element.id"
                @click="cardModal(element)"
            >
                <div class="card-headers row">
                    <div class="content-header my-2">
                        <h6
                            class="mb-0 text-capitalize d-flex justify-content-start align-items-center"
                        >
                            {{ element.name }}
                        </h6>
                    </div>
                </div>
                <span
                    v-if="element.start_do == null"
                    class="badge badge-light-warning mt-2 me-2"
                    ><i
                        class="fa-solid fa-circle-exclamation me-2 text-danger"
                    ></i
                    >Pilih Tanggal Mengerjakan {{ element.start_do }}</span
                >
                <span
                    v-if="element.finish_do == null"
                    class="badge badge-light-danger mt-2 me-2"
                    ><i
                        class="fa-solid fa-circle-exclamation me-2 text-danger"
                    ></i
                    >Pilih tanggal Selesai</span
                >
                <span
                    v-if="element.report == null"
                    class="badge badge-light-info mt-2 me-2"
                    ><i
                        class="fa-solid fa-circle-exclamation me-2 text-danger"
                    ></i
                    >Upload Laporan</span
                >
                <div class="separator mb-2 mt-3"></div>
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
                            <img
                                height="25"
                                width="25"
                                :src="staf.foto"
                                alt=""
                                @error="onErrorImg"
                            />
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
    data(){
        return {
            doList: [],
        }
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
    watch:{
        doing(){
            this.doList = this.doing
        }
    },
    methods: {
        onErrorImg(e) {
            this.$parent.onErrorImg(e);
        },
        cardModal(data) {
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
.disable_pointer {
    pointer-events: none;
}
.alert.alert-primary {
    background-color: #cfe2ffa1;
}
.avatar {
    border: 2px solid rgb(64, 95, 249);
}
.bg-primary-custom {
    background-color: #009ef76e;
    color: #aae0ff;
}
.card-header-doing {
    background-color: #d6d6d6;
}
</style>
