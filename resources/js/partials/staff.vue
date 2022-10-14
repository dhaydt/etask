<template>
    <div class="p-2 staff-repo card-list w-100">
        <div class="list-header mb-4 fw-bold">
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
            >
                <button
                    @click="removeStaff(element.id)"
                    class="btn btn-sm btn-danger btn-hover-scale p-1 btn-remove position-absolute text-light"
                    data-bs-toggle="tooltip"
                >
                    <i class="fas fa-trash px-1 py-0"></i>
                </button>
                <div class="align-items-center d-flex justify-content-center">
                    <div class="avatar me-2 text-capitalize position-relative">
                        <img width="25" height="25" :src="element.foto" alt="" @error="onErrorImg" />
                    </div>
                </div>
                <div
                    class="staff-name d-flex justify-content-center align-items-start flex-column"
                >
                    <div class="staff-label">
                        {{ element.name }}
                    </div>
                    <label
                        v-if="element.available == 1"
                        class="badge bg-success rounded-pill staff-status"
                        >Available</label
                    >
                    <label
                        v-else
                        class="badge bg-danger rounded-pill staff-status"
                        >OnWorking</label
                    >
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
    data() {
        return {
            config: {
                headers: {
                    header: document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            },
        };
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
        onErrorImg(e){
            this.$parent.onErrorImg(e);
        },
        checkStaff(evt) {
            this.$parent.checkStaff(evt);
        },
        removeStaff(id) {
            var user = {
                nip: id,
            };
            var that = this;
            axios
                .post(
                    "addStaff",
                    {
                        user: user,
                        status: true,
                    },
                    this.config
                )
                .then(function (resp) {
                    var data = resp.data;
                    if (data.code == 200) {
                        that.$parent.splitAxios(data.data.original);
                        // Vue.$toast.success(data.message);
                        console.log("respon", data);
                    }
                    that.$parent.refreshSkpd();
                })
                .catch(function (err) {
                    console.log("err", err);
                    // window.alert(err);
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.staff-repo {
    background: #c9f7f59c;
    color: #00fff3;
}

.list-group-staff{
    button{
        z-index: 1;
    }
}
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
