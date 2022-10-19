<template>
    <!-- Modal -->
    <div
        class="modal fade modal-detail"
        id="modalTask"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <form
                    @submit="formSubmit"
                    name="cardTask"
                    enctype="multipart/form-data"
                >
                    <div class="modal-header position-relative pt-1 pb-0">
                        <label
                            v-if="status == 'todo'"
                            class="label-row bg-warning text-capitalize text-dark fw-bold d-flex align-items-center"
                            ><i
                                class="fa-solid text-dark fa-list-check me-2"
                            ></i
                            ><span>{{ newStat }}</span></label
                        >
                        <label
                            v-if="status == 'doing'"
                            class="label-row bg-info text-capitalize text-light fw-bold"
                            ><i class="fa-solid fa-spinner text-light me-2"></i
                            ><span>{{ newStat }}</span></label
                        >
                        <label
                            v-if="status == 'done'"
                            class="label-row bg-success text-capitalize text-light fw-bold"
                            ><i class="fa-solid fa-check text-light me-2"></i
                            ><span>{{ newStat }}</span></label
                        >
                        <h5 class="modal-title w-100" id="exampleModalLabel">
                            <i class="fa-regular fa-window-maximize"></i>
                            <input
                                type="text"
                                class="form-control header"
                                id="name"
                                placeholder="Judul Task"
                                name="name"
                                v-model="taskData.name"
                            />
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
                    <div class="modal-body pt-1">
                        <LabelTitle
                            title="Staff"
                            icon="fa-solid fa-users"
                        ></LabelTitle>
                        <div class="mb-3 input-text">
                            <div class="" v-if="newStaff.length < 1">
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
                                            :src="s.foto"
                                            @error="onErrorImg"
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
                                v-on:input="getId"
                                label="name"
                                placeholder="Pilih Staff"
                                :components="{ Deselect }"
                            ></v-select>
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
                                :disabled="taskData.status == 'doing'"
                                placeholder="Pilih dasar SPT"
                                :components="{ Deselect }"
                            ></v-select>
                        </div>
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
                            <input
                                type="date"
                                v-model="start"
                                name="start"
                                class="form-control"
                                :disabled="taskData.status == 'doing'"
                            />
                        </div>
                        <LabelTitle
                            v-if="status == 'doing'"
                            title="Mulai mengerjakan"
                            icon="fa-solid fa-stopwatch"
                        ></LabelTitle>
                        <div class="mb-3 input-text" v-if="status == 'doing'">
                            <datetime
                                class="form-control"
                                type="datetime"
                                v-model="start_on"
                                use24-hour
                            ></datetime>
                        </div>
                        <LabelTitle
                            v-if="status == 'doing'"
                            title="Selesai mengerjakan"
                            icon="fa-solid fa-stopwatch"
                        ></LabelTitle>
                        <div class="mb-3 input-text" v-if="status == 'doing'">
                            <datetime
                                class="form-control"
                                type="datetime"
                                v-model="finish_on"
                                use24-hour
                            ></datetime>
                        </div>

                        <div class="mb-5" v-if="status == 'doing'">
                            <LabelTitle
                                title="Upload Laporan"
                                icon="fa-solid fa-upload"
                            ></LabelTitle>
                            <input
                                type="file"
                                name="file"
                                v-on:change="onFileChange"
                                accept="application/pdf,application/vnd.ms-excel,.docx, image/jpeg, .png, .jpg, .jpeg"
                                id="pilih_file"
                                hidden
                            />
                            <div class="text-center">
                                <label
                                    for="pilih_file"
                                    class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Upload File"
                                >
                                    <i class="fa-solid fa-file"></i>
                                    {{ fileName ? fileName : "Pilih File" }}
                                </label>
                                <div class="border p-2 mt-3">
                                    <p>Preview Here:</p>
                                    <template v-if="preview">
                                        <img :src="preview" class="img-fluid" />
                                        <p class="mb-0">
                                            file name: {{ image.name }}
                                        </p>
                                        <p class="mb-0">
                                            size:
                                            {{
                                                Math.round(
                                                    (image.size / 1024 / 1024) *
                                                        100
                                                ) / 100
                                            }}MB
                                        </p>
                                    </template>
                                </div>
                            </div>
                            <div v-if="isUploading" class="progress mt-5">
                                <div
                                    class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                    role="progressbar"
                                    aria-label="Animated striped example"
                                    aria-valuenow="75"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                    x-bind:style="`width: ${progress}%`"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div v-if="role == 1" class="modal-footer">
                        <!-- <button v-if="newStaff.length !== 0 && dasarSpt.length !== 0 && taskData.description !== null && start !== null "
                            class="btn btn-success me-auto"
                            @click.prevent="mulaiTask(taskData.id, status)"
                        >
                            <label v-if="status == 'todo'">Mulai Task</label>
                            <label v-else>Selesaikan Task</label>
                        </button> -->
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Tutup
                        </button>
                        <button class="btn btn-primary" type="submit">
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
                            @click.prevent="mulaiTask(taskData.id, status)"
                        >
                            <label v-if="status == 'todo'">Mulai Task</label>
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
import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";

export default {
    components: { LabelTitle, vSelect, Datetime },
    props: {
        taskData: Object & Array,
        role: String,
        status: String,
        staffs: Array,
        dasar: Array,
        selected: Array,
        updateFlat: Array,
    },
    data() {
        return {
            dasarNew: [],
            start: null,
            options: [],
            start_on: null,
            finish_on: null,
            newStat: null,
            spt: ["SURAT 1", "SURAT 2", "SURAT 3"],
            sptList: ["SURAT 1", "SURAT 2"],
            token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            config: {
                headers: {
                    headers: document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            },
            newStaff: [],
            dasarSpt: [],

            fileName: [],
            file: [],
            isUploading: false,
            preview: null,
            Deselect: {
                render: (createElement) => createElement("span", "❌"),
            },
        };
    },
    watch: {
        dasar() {
            this.dasarUpdate(this.dasar);
        },
        role() {
            this.UpdateRole(this.role);
        },
        status() {
            this.checkStatus(this.status);
        },
        taskData() {
            console.log("modal.taskData", this.taskData);
            this.checkStaff();
            if (this.status == "doing") {
                if (this.taskData.start_do !== null) {
                    this.start_on = new Date(
                        this.taskData.start_do
                    ).toISOString();
                }

                if (this.taskData.finish_do !== null) {
                    this.finish_on = new Date(
                        this.taskData.finish_do
                    ).toISOString();
                }
                this.fileName = this.taskData.report;
            }
        },
        staffs() {
            this.options = this.staffs;
            this.newStaff = this.selected;
            if (this.selected.length > 0) {
                this.updateSelectStaff(this.options, this.selected);
            }
        },
        selected() {
            this.options = this.staffs;
            this.newStaff = this.selected;
            console.log("watch selected", this.options);
            if (this.selected.length > 0) {
                this.updateSelectStaff(this.options, this.selected);
            }
        },
    },
    methods: {
        onFileChange(event) {
            //console.log(e.target.files[0]);
            this.fileName = event.target.files[0].name;
            this.file = event.target.files[0];
            console.log("img", event.target.files[0]);
            var input = event.target;
            if (input.files) {
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                };
                this.image = input.files[0];
                reader.readAsDataURL(input.files[0]);
            }
        },
        onErrorImg(e) {
            this.$parent.onErrorImg(e);
        },
        getId(e) {
            console.log("event", e);
            this.updateSelectStaff(this.staffs, e);
        },
        dasarUpdate(data) {
            var valData = [];
            data.forEach(function (val, i) {
                if (val.status == 1) {
                    valData.push(val);
                }
            });
            this.dasarNew = valData;
        },
        checkStaff() {
            // this.newStaff = this.taskData.staffs;
            this.start = this.taskData.start;
            this.dasarSpt = this.taskData.dasar;
        },
        updateSelectStaff(options, selected) {
            if (selected.length > 0) {
                var filtered;
                for (let i = 0; i < selected.length; i++) {
                    if (i == 0) {
                        var data = options;
                        const filter = data.reduce(
                            (acc, el) =>
                                (el.id === selected[i].id && acc) || [
                                    ...acc,
                                    el,
                                ],
                            []
                        );
                        filtered = filter;
                    } else {
                        const filter = filtered.reduce(
                            (acc, el) =>
                                (el.id === selected[i].id && acc) || [
                                    ...acc,
                                    el,
                                ],
                            []
                        );
                        filtered = filter;
                    }
                }
                this.options = filtered;
            } else {
                this.options = options;
            }
        },
        checkStatus(stat) {
            if (stat == "todo") {
                this.newStat = "To - Do";
            } else if (stat == "doing") {
                this.newStat = "Doing";
            } else {
                this.newStat = "Done / Finished";
            }
        },
        mulaiTask(id, status) {
            const that = this;
            if (status == "todo") {
                status = "doing";
            } else {
                status = "done";
            }
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
        formSubmit(e) {
            e.preventDefault();
            let that = this;

            var id = this.taskData.id;
            var name = this.taskData.name;
            var description = this.taskData.description;
            var staf = this.newStaff;
            var dasar = this.dasarSpt;
            var start = this.start;
            var start_on = this.start_on;
            var finish_on = this.finish_on;
            var file = this.file;

            if (dasar == undefined) {
                var dasar = [];
            }

            if (this.status == "todo") {
                if (name == null || name == "") {
                    Vue.$toast.warning("Nama task tidak boleh kosong!!");
                } else if (description == null || description == "") {
                    Vue.$toast.warning("Mohon isi deskripsi task!!");
                } else if (staf.length == 0) {
                    Vue.$toast.warning("Mohon pilih staff!");
                } else if (dasar.length == 0) {
                    Vue.$toast.warning("Mohon pilih dasar SPT!");
                } else if (start == null) {
                    Vue.$toast.warning(
                        "Mohon masukan tanggal pengerjaan task!"
                    );
                } else {
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
            } else if (this.status == "doing") {
                if (start_on == null || start_on == "") {
                    Vue.$toast.warning("Mohon isi tanggal mengerjakan!");
                } else if (finish_on == "" || description == null) {
                    Vue.$toast.warning(
                        "Mohon isi tanggal selesai mengerjakan!"
                    );
                }
                // else if (file.name) {
                //     Vue.$toast.warning(
                //         "Mohon upload foto atau dokumen laporan!"
                //     );
                // }
                else {
                    var formData = new FormData();

                    formData.append("file", file);
                    console.log("file", file);
                    console.log("formData", ...formData.entries());

                    // var dataSend = {
                    //             id: id,
                    //             name: name,
                    //             staf: staf,
                    //             description: description,
                    //             start: start,
                    //             dasar: dasar,
                    //             start_on: start_on,
                    //             finish_on: finish_on,
                    //             file: formData,
                    //         },

                    // $.ajax({
                    //     url: "/updateTask",
                    //     method: "post",
                    //     data: JSON.stringify(dataSend), // Replace 'this' with self''
                    //     contentType: "multypart/",
                    //     dataType: "json",
                    //     context: this,
                    //     success: function (res) {
                    //         $("#res").html(res);
                    //     },
                    // })

                    const config = {
                        headers: {
                            "Content-Type":
                                "multipart/form-data; charset=utf-8; boundary=" +
                                Math.random().toString().substr(2),
                        },
                    };

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
                                start_on: start_on,
                                finish_on: finish_on,
                                file: formData,
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
            }
        },
    },
};
</script>

<style lang="scss" scoped>
#modalTask .modal-dialog {
    .modal-content {
        padding: 0 1rem;
        overflow: scroll;

        .modal-body {
            height: 70vh;
            overflow: scroll;
        }
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
    width: 90%;
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
    position: absolute;
    top: 0;
    left: -15px;
    z-index: 1;
    font-size: 12px;
    margin-left: 35px;
    color: #84868a;
    padding: 6px 15px;
    border-radius: 0 0 4px 4px;
}
.avatar-card label {
    font-family: "Acme", sans-serif;
    font-size: 12px;
}
</style>
