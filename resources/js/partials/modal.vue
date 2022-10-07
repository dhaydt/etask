<template>
    <!-- Modal -->
    <div
        class="modal fade"
        id="modalTask"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/updateTask" method="POST" name="cardTask">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fa-regular fa-window-maximize"></i>
                            <input
                                type="text"
                                class="form-control header"
                                id="name"
                                placeholder="Judul Task"
                                name="name"
                                v-model="taskData.name"
                            />
                            <label class="label-row">in list To-do</label>
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>

                    <input type="hidden" name="_token" :value="token" />
                    <input type="hidden" name="id" :value="taskData.id" />
                    <div class="modal-body">
                        <LabelTitle
                            title="Deskripsi"
                            icon="fa-solid fa-align-justify"
                        ></LabelTitle>
                        <div class="mb-3 input-text">
                            <textarea
                                class="form-control"
                                id="description"
                                rows="5"
                                name="description"
                                v-model="taskData.description"
                            ></textarea>
                        </div>
                        <LabelTitle
                            title="Mulai"
                            icon="fas fa-calendar"
                        ></LabelTitle>
                        <div class="mb-3 input-text">
                            <input type="date" v-model="start" class="form-control" />
                        </div>
                        <LabelTitle
                            title="Dasar Surat Perintah Tugas"
                            icon="fas fa-tasks"
                        ></LabelTitle>
                        <div class="mb-3 input-text">
                            <v-select
                                class="mt-2"
                                :options="dasarNew"
                                multiple
                                v-model="dasarSpt"
                                label="dasar"
                                placeholder="Pilih dasar SPT"
                                :components="{ Deselect }"
                            ></v-select>
                        </div>

                        <LabelTitle
                            title="Staff"
                            icon="fa-solid fa-users"
                        ></LabelTitle>
                        <div class="mb-3 input-text">
                            <div class="" v-if="newStaff == '[]'">
                                <span class="badge rounded-pill bg-danger"
                                    >Belum ada Staff dipilih!</span
                                >
                            </div>
                            <div class="avatar-list flex-row row mt-2" v-else>
                                <div
                                    v-for="s in newStaff"
                                    class="avatar-card d-flex col-12 col-md-6 align-items-center"
                                    :key="s.id"
                                >
                                    <div class="avatar me-2">
                                        <img
                                            src="img/user.png"
                                            height="25"
                                            alt=""
                                        />
                                    </div>
                                    <label for="" class="text-capitalize">{{
                                        s.name
                                    }}</label>
                                </div>
                            </div>
                            <v-select
                                class="mt-2"
                                :options="options"
                                multiple
                                v-model="newStaff"
                                label="name"
                                placeholder="Pilih Staff"
                                :components="{ Deselect }"
                            ></v-select>
                        </div>
                    </div>
                    <div v-if="role == 1" class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Tutup
                        </button>
                        <button
                            class="btn btn-primary"
                            @click.prevent="saveTask"
                        >
                            Simpan Task
                        </button>
                    </div>
                    <div
                        v-if="role == 2 && newStat !== 'finish'"
                        class="modal-footer"
                    >
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Tutup
                        </button>
                        <button
                            class="btn btn-success"
                            @click.prevent="mulaiTask(taskData.id, newStat)"
                        >
                            <label v-if="newStat == 'doing'">Mulai Task</label>
                            <label v-else>Selesaikan Task</label>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import LabelTitle from "./part/label-title";
import vSelect from "vue-select";
export default {
    components: { LabelTitle, vSelect },
    props: {
        taskData: Object & Array,
        role: String,
        status: String,
        staffs: Array,
        dasar: Array
    },
    data() {
        return {
            dasarSpt: [],
            dasarNew:[],
            start: null,
            options: this.staffs,
            newStat: null,
            spt: ['SURAT 1', 'SURAT 2', 'SURAT 3'],
            sptList: ['SURAT 1', 'SURAT 2',],
            token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            config: {
                headers: {
                    header: document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            },
            newStaff: [],
            Deselect: {
                render: (createElement) => createElement("span", "❌"),
            },
        };
    },
    watch: {
        dasar(){
            this.dasarUpdate(this.dasar);
        },
        role() {
            this.UpdateRole(this.role);
        },
        status() {
            this.checkStatus(this.status);
        },
        taskData() {
            this.checkStaff();
        },
    },
    methods: {
        dasarUpdate(data){
            var valData = [];
            data.forEach(function(val, i){
                if(val.status == 1){
                    valData.push(val);
                }
            });
            this.dasarNew = valData;
        },
        checkStaff() {
            this.newStaff = this.taskData.staffs;
            this.start = this.taskData.start;
            this.dasarSpt = this.taskData.dasar
        },
        checkStatus(stat) {
            if (stat == "todo") {
                this.newStat = "doing";
            } else if (stat == "doing") {
                this.newStat = "done";
            } else {
                this.newStat = "finish";
            }
        },
        mulaiTask(id, status) {
            const that = this;
            axios
                .post(
                    "/taskStatus",
                    {
                        id: id,
                        status: status,
                    },
                    this.config
                )
                .then(function (resp) {
                    that.$parent.splitAxios(resp.data.original);
                    that.sembunyi();
                    Vue.$toast.success("Status task berhasil di update!");
                })
                .catch(function (err) {
                    window.alert(err);
                });
        },
        UpdateRole(role) {
            if (role == 2) {
                document
                    .getElementById("name")
                    .setAttribute("disabled", "disabled");
                document
                    .getElementById("description")
                    .setAttribute("disabled", "disabled");
            }
        },
        sembunyi() {
            this.$parent.hideModalTask();
        },
        saveTask(e) {
            var id = this.taskData.id;
            var name = this.taskData.name;
            var description = this.taskData.description;
            var staf = this.newStaff;
            var dasar = this.dasarSpt;
            var start = this.start;
            const that = this;
            if(dasar == undefined){
                var dasar = [];
            }
            if (name == "" || name == null) {
                Vue.$toast.warning("Judul task tidak boleh kosong!");
            } else if (description == "" || description == null) {
                Vue.$toast.warning("Deskripsi task tidak boleh kosong!");

            } else if(dasar.length == 0){
                Vue.$toast.warning("Mohon pilih Dasar SPT!");
            }
            else {
                axios
                    .post(
                        "/updateTask",
                        {
                            id: id,
                            name: name,
                            staf: staf,
                            description: description,
                            start: start,
                            dasar: dasar,
                        },
                        this.config
                    )
                    .then(function (response) {
                        that.sembunyi();
                        that.$parent.splitAxios(response.data.original);
                        Vue.$toast.success("Task Updated Successfully");
                    })
                    .catch(function (err) {
                        Vue.$toast.error(err);
                    });
            }
        },
    },
};
</script>

<style lang="scss" scoped>
#modalTask .modal-dialog {
    .modal-content {
        padding: 0 1rem;
    }
    max-width: 700px;
    .btn-close {
        margin-top: -35px;
        font-size: 13px;
        color: black;
        font-weight: 800;
    }

    i {
        font-size: 16px;
        color: #67696f;
    }
}
input.form-control.header {
    border: none;
    display: unset;
    width: auto;
    font-family: "Acme", sans-serif;
    font-weight: 600;
    padding-bottom: 2px;
    font-size: 20px;
    color: #000;
    text-transform: capitalize;
}
.mb-3.input-text {
    padding-left: 32px;
    .form-control {
        border: none;
        background-color: #ededed;
        margin-top: 10px;
    }
    #description {
        font-family: cursive;
        font-size: 12px;
    }
}
.modal-content .modal-header {
    border-bottom: none;
}

.label-row {
    display: block;
    font-size: 12px;
    margin-left: 35px;
    color: #84868a;
}
.avatar-card label {
    font-family: "Acme", sans-serif;
    font-size: 12px;
}
</style>
