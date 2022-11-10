<template>
    <div class="container-fluid position-relative" style="margin-top: 20px">
        <div class="row pt-4 position-relative">
            <AddStaff></AddStaff>
            <div class="loader d-flex justify-content-center">
                <beat-loader
                    :loading="loading"
                    color="white"
                    size="30px"
                ></beat-loader>
            </div>
            <div class="col-3">
                <div
                    class="p-2 task-repo text-dark fw-bold card-list w-100 m-0"
                >
                    <div class="list-header mb-4">
                        <span class="list-drag-handle">&#x2630;</span>
                        To - Do
                    </div>
                    <!-- Backlog draggable component. Pass arrBackLog to list prop -->
                    <draggable
                        v-bind="dragOptions"
                        class="list-group kanban-column"
                        id="todo"
                        :list="newTodos"
                        group="tasks"
                        :move="checkMove"
                    >
                        <div
                            class="list-group-item overflow-hidden text-capitalize mb-4"
                            v-for="element in newTodos"
                            :key="element.id"
                            @click="cardModal(element)"
                        >
                            <div class="card-headers row">
                                <div class="content-header my-2 col-10">
                                    <h6
                                        class="mb-0 text-capitalize d-flex justify-content-between align-items-center"
                                    >
                                        {{ element.name }}
                                    </h6>
                                </div>
                                <div
                                    class="col-2 d-flex justify-content-center align-items-center"
                                >
                                    <button
                                        @click="removeTask(element.id)"
                                        class="btn delete-btn btn-sm text-danger"
                                        data-bs-toggle="tooltip"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div
                                v-if="element.tipe_dinas !== null"
                                class="d-flex position-relative"
                            >
                                <span
                                    v-if="element.tipe_dinas == 'SPPD'"
                                    class="badge-tipe badge badge-light-success text-success ms-auto mb-2"
                                    >{{ element.tipe_dinas }}</span
                                >
                                <span
                                    v-else
                                    class="badge-tipe badge badge-light-danger text-danger ms-auto mb-2"
                                    >{{ element.tipe_dinas }}</span
                                >
                            </div>
                            <div
                                v-if="element.description"
                                class="description ps-2 mb-4"
                            >
                                <span>
                                    {{ element.description }}
                                </span>
                            </div>
                            <draggable
                                class="list-staff"
                                :id="element.id"
                                :list="element.staffs"
                                group="task-staff"
                            >
                                <div
                                    class="list-group-staff bg-light-success d-flex card flex-row shadow-sm p-1 mb-1"
                                    v-for="staf in element.staffs"
                                    :key="staf.id"
                                >
                                    <a
                                        v-if="
                                            element.staffs.length !== 0 &&
                                            element.dasar.length !== 0 &&
                                            element.description !== null &&
                                            element.start !== null &&
                                            element.tipe_dinas == 'SPPD'
                                        "
                                        :href="
                                            'generate_sppd/' +
                                            element.id +
                                            '/' +
                                            staf.id
                                        "
                                        target="_blank"
                                        data-bs-toggle="tooltip"
                                        title="Cetak SPPD"
                                        @click="generateSppd()"
                                        class="btn btn-sm btn-primary p-1 btn-hover-scale btn-print position-absolute text-light"
                                    >
                                        <i
                                            class="fa-solid fa-print ms-1 text-light"
                                        ></i>
                                    </a>
                                    <button
                                        @click="
                                            removeStaff(staf.id, element.id)
                                        "
                                        class="btn btn-sm btn-danger p-1 btn-hover-scale btn-remove position-absolute text-light"
                                        data-bs-toggle="tooltip"
                                    >
                                        <i class="fas fa-trash px-1 py-0"></i>
                                    </button>
                                    <div
                                        class="avatar me-2 text-capitalize position-relative"
                                        data-bs-toggle="tooltip"
                                    >
                                        <img
                                            height="25"
                                            width="25"
                                            :src="staf.foto"
                                            alt=""
                                            @error="onErrorImg"
                                        />
                                    </div>
                                    <div
                                        class="staff-name d-flex justify-content-center"
                                    >
                                        <div class="staff-label">
                                            {{ staf.nama }}
                                        </div>
                                    </div>
                                </div>
                            </draggable>
                            <span
                                v-if="element.staffs.length == 0"
                                class="badge badge-light-danger mt-2 me-2"
                                ><i
                                    class="fa-solid fa-circle-exclamation me-2 text-danger"
                                ></i
                                >Pilih staff</span
                            >
                            <span
                                v-if="element.dasar.length == 0"
                                class="badge badge-light-warning mt-2 me-2"
                                ><i
                                    class="fa-solid fa-circle-exclamation me-2 text-danger"
                                ></i
                                >Pilih Dasar SPT</span
                            >
                            <span
                                v-if="element.description == null"
                                class="badge badge-light-danger mt-2 me-2"
                                ><i
                                    class="fa-solid fa-circle-exclamation me-2 text-danger"
                                ></i
                                >Isi Deskripsi</span
                            >
                            <span
                                v-if="element.start == null"
                                class="badge badge-light-info mt-2 me-2"
                                ><i
                                    class="fa-solid fa-circle-exclamation me-2 text-danger"
                                ></i
                                >Masukan Tanggal Mulai</span
                            >
                            <span
                                v-if="element.tipe_dinas == null"
                                class="badge badge-light-warning mt-2 me-2"
                                ><i
                                    class="fa-solid fa-circle-exclamation me-2 text-danger"
                                ></i
                                >Pilih Tipe Dinas</span
                            >
                            <span
                                v-if="
                                    element.staffs.tipe_dinas == 'SPPD' &&
                                    element.start_do == null
                                "
                                class="badge badge-light-danger mt-2 me-2"
                                ><i
                                    class="fa-solid fa-circle-exclamation me-2 text-danger"
                                ></i
                                >Pilih Tanggal Mulai Kedinasan</span
                            >
                            <span
                                v-if="
                                    element.staffs.tipe_dinas == 'SPPD' &&
                                    element.finish_do == null
                                "
                                class="badge badge-light-danger mt-2 me-2"
                                ><i
                                    class="fa-solid fa-circle-exclamation me-2 text-danger"
                                ></i
                                >Pilih Tanggal Selesai Kedinasan</span
                            >
                            <div class="separator my-3"></div>
                            <div
                                class="created-at mt-3 d-flex justify-content-between"
                            >
                                <div
                                    class="dated-star align-items-center d-flex"
                                >
                                    <span
                                        v-if="element.start"
                                        data-bs-toggle="tooltip"
                                        title="Tanggal mulai pengerjaan"
                                        class="badge text-danger d-flex align-items-center"
                                    >
                                        <i
                                            class="fa-solid fa-calendar me-2 text-danger"
                                        ></i>
                                        <span class="pt-1">
                                            {{ element.start | moment }}
                                        </span>
                                    </span>

                                    <span
                                        v-else
                                        class="badge rounded-pill badge-warning"
                                    >
                                        Pengerjaan belum diatur
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <a
                                        v-if="
                                            element.staffs.length !== 0 &&
                                            element.dasar.length !== 0 &&
                                            element.description !== null &&
                                            element.start !== null &&
                                            element.tipe_dinas !== null
                                        "
                                        :href="'generate_spt/' + element.id"
                                        target="_blank"
                                        data-bs-toggle="tooltip"
                                        title="Generate Surat Tugas"
                                        @click="generateSpt(element.id)"
                                        class="btn btn-primary btn-hover-rotate-start pe-3 ps-4 py-2 me-2"
                                    >
                                        <i
                                            class="fa-solid fa-print text-light"
                                        ></i>
                                    </a>
                                    <button
                                        v-if="
                                            element.staffs.length !== 0 &&
                                            element.dasar.length !== 0 &&
                                            element.description !== null &&
                                            element.start !== null &&
                                            element.tipe_dinas !== null
                                        "
                                        data-bs-toggle="tooltip"
                                        title="Mulai Task"
                                        class="btn btn-sm btn-primary btn-hover-rotate-end pe-3 ps-4 py-2"
                                        @click="
                                            mulaiTask(
                                                element.id,
                                                element.status
                                            )
                                        "
                                    >
                                        <i
                                            class="fa-regular fa-circle-play"
                                        ></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="input-group input-group-sm mt-auto">
                            <input
                                v-model="newTask"
                                id="input-2"
                                placeholder="Tambah tugas"
                                class="form-control"
                                required
                                @keyup.enter="add"
                            />
                            <span
                                id="basic-addon2"
                                class="input-group-text p-0"
                            >
                                <button
                                    type="submit"
                                    :disabled="newTask.length === 0"
                                    class="button is-primary btn btn-success btn-sm"
                                    @click="add"
                                >
                                    <span class="icon is-small btn btn-sm p-0">
                                        <i class="fa-solid fa-save"></i>
                                    </span>
                                </button>
                            </span>
                        </div>
                    </draggable>
                </div>
            </div>
            <div class="col-3">
                <Doing :doing="newDoing"></Doing>
            </div>
            <div class="col-3">
                <Done :done="newDone"></Done>
            </div>
            <div v-if="role == 1" class="col-3">
                <Staff :staffs="newStaff"></Staff>
            </div>
        </div>
        <Modal
            :taskData="taskData"
            :role="role"
            :status="status"
            :staffs="newStaff"
            :dasar="dasar"
            :selected="selected"
            :kendaraan="kendaraan"
            :updateFlat="updateFlat"
        ></Modal>
        <Loading v-if="loadingAsn"></Loading>
    </div>
</template>

<script>
import Loading from "../partials/part/loading";
import AddStaff from "../partials/part/addStaff";
import Modal from "../partials/modal";
import Staff from "../partials/staff";
import Done from "../partials/done";
import Doing from "../partials/doing";
import draggable from "vuedraggable";
import axios from "axios";
import moment from "moment";
import BeatLoader from "vue-spinner/src/BeatLoader.vue";
import Swal from "sweetalert2";

export default {
    name: "e-task",
    components: {
        Loading,
        AddStaff,
        Swal,
        Modal,
        Staff,
        Done,
        Doing,
        BeatLoader,
        draggable,
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
    data() {
        return {
            newTask: "",
            status: null,
            loadingAsn: false,
            staffSingle: [],
            selected: [],
            newTodos: [],
            newDoing: [],
            newDone: [],
            newStaff: [],
            newDasarList: [],
            kendaraan: null,
            taskTitle: null,
            taskDescription: null,
            taskStaff: [],
            taskData: [],
            newDasar: [],
            updateFlat: null,
            loading: false,
        };
    },
    mounted() {
        this.splitData();
        this.mountSkpd();
    },
    props: {
        todos: Array,
        doing: Array,
        done: Array,
        staffs: Array,
        role: String,
        dasar: Array,
    },
    filters: {
        moment: function (date) {
            return moment(date).format("D MMMM YYYY");
        },
    },
    methods: {
        mountSkpd() {
            // this.loadingAsn = true;
            var id_skpd = localStorage.getItem("id_skpd");
            var that = this;

            axios
                .post("pegawaiSkpd", {
                    id_skpd: id_skpd,
                })
                .then(function (resp) {
                    // console.log('bcrypt', resp.data)
                    if (resp.data.code == 200) {
                        var dataSkpd = resp.data.data;

                        var dataMentah = resp.data.dataMentah;


                        that.groupSkpd(dataMentah);

                        var user = resp.data.user;
                        var selected = [];
                        localStorage.removeItem("semuaPegawaiSkpd");
                        localStorage.setItem(
                            "semuaPegawaiSkpd",
                            JSON.stringify(dataSkpd)
                        );
                        // console.log("skpd", resp.data);
                        dataSkpd.forEach(function (item, i) {
                            user.filter((u) => {
                                // return u.id.toString() === item.nip.toString();
                                if (u.nip_terkait === item.nip) {
                                    selected.push(item);
                                }
                            });
                        });
                        console.log("dataSkpd", selected);

                        that.loadingAsn = false;
                        Vue.$toast.success(
                            "Data ASN Terkait berhasil di update.."
                        );

                        that.$root.$emit("toggleAdd");

                        localStorage.setItem(
                            "asnTerdaftar",
                            JSON.stringify(selected)
                        );
                        // that.dataPegawai = selected;
                        that.$root.$emit("updateDataPeg");
                        that.$root.$emit("getListSkpd");
                    }
                })
                .catch(function (err) {
                    console.log("err skpd", err);
                });
        },
        groupSkpd(data) {
            // var skpd = Object.keys(data);
            localStorage.setItem('listSkpd', JSON.stringify(data));
        },
        arrayCheck(object) {
            var stringConstructor = "test".constructor;
            var arrayConstructor = [].constructor;
            var objectConstructor = ({}).constructor;
            if (object === null) {
                return "null";
            }
            if (object === undefined) {
                return "undefined";
            }
            if (object.constructor === stringConstructor) {
                return "String";
            }
            if (object.constructor === arrayConstructor) {
                return "Array";
            }
            if (object.constructor === objectConstructor) {
                return "Object";
            }
            {
                return "don't know";
            }
        },
        refreshSkpd() {
            var that = this;
            axios.get("staffList").then(function (resp) {
                console.log("staffGet", resp);
                localStorage.setItem("asnTerdaftar", JSON.stringify(resp.data));

                console.log("staffList", resp.data);
                var dataSkpd = JSON.parse(
                    localStorage.getItem("semuaPegawaiSkpd")
                );
                var selected = [];
                var allStaff = [];
                var user = resp.data;
                console.log("user", user);
                dataSkpd.forEach(function (item, i) {
                    user.filter((u) => {
                        console.log(
                            "filter",
                            u.nip_terkait === item.nip,
                            u,
                            item.nip
                        );
                        if (u.nip_terkait === item.nip) {
                            selected.push(item);
                        }
                    });
                    allStaff.push(item);
                });
                console.log("selec", selected);
                // that.loadingAsn = false;
                // Vue.$toast.success("Data ASN Terkait berhasil di update..");
                // that.namaSkpd = selected[0].nama_skpd;
                localStorage.setItem(
                    "semuaPegawaiSkpd",
                    JSON.stringify(allStaff)
                );
                localStorage.setItem("asnTerdaftar", JSON.stringify(selected));
                // that.$root.$emit("updateDataPeg");
                that.$root.$emit("returnStaff");
            });
        },
        generateSpt(id) {
            event.stopPropagation();
        },
        mulaiTask(id, status) {
            event.stopPropagation();
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
                    that.splitAxios(resp.data.original);
                    that.hideModalTask();
                    Vue.$toast.success("Status task berhasil di update!");
                })
                .catch(function (err) {
                    window.alert(err);
                });
        },
        backTask(id, status) {
            event.stopPropagation();
            const that = this;
            if (status == "doing") {
                status = "todo";
            } else {
                status = "doing";
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
                    that.splitAxios(resp.data.original);
                    that.hideModalTask();
                    Vue.$toast.success("Status task berhasil di update!");
                })
                .catch(function (err) {
                    window.alert(err);
                });
        },
        cardModal(data) {
            if (data.status == "todo" || data.status == "doing") {
                var modalTask = new bootstrap.Modal(
                    document.getElementById("modalTask"),
                    {
                        keyboard: false,
                    }
                );
            } else {
                var modalTask = new bootstrap.Modal(
                    document.getElementById("modal_done"),
                    {
                        keyboard: true,
                    }
                );
            }
            this.taskData = data;
            this.status = data.status;
            this.selected = data.staffs;
            this.kendaraan = data.kendaraan;
            this.refreshStaff();
            this.updateFlat = this.selected;
            modalTask.show();
        },
        refreshStaff() {
            this.newStaff = [];
            this.staffs.forEach((s) => {
                this.newStaff.push(s);
            });
        },
        onErrorImg(e) {
            e.target.src = "img/user.png";
        },
        updateDasarStatus(data) {
            this.$parent.updateDasarStatus(data);
        },
        toggleLoading(val) {
            this.loading = val;
        },
        moment: function (date) {
            return moment(date);
        },
        removeTask(taskId) {
            event.stopPropagation();
            const that = this;
            Swal.fire({
                title: "Attention!",
                text: "Yakin ingin menghapus task ini?",
                icon: "warning",
                confirmButtonText: "Ya hapus aja !",
                confirmButtonColor: "#14bb6e",
                cancelButtonColor: "#ff0033",
                showCancelButton: true,
                reverseButtons: true,
                cancelButtonText: "Ga jadi deh !",
            }).then(function (result) {
                // console.log(result);
                if (result.isConfirmed == true) {
                    axios
                        .post("deleteTask", {
                            task_id: taskId,
                        })
                        .then(function (response) {
                            console.log("resp", response);
                            that.splitAxios(response.data.original);
                            // Vue.$toast.success("Task berhasil dihapus");
                            Swal.fire({
                                icon: "success",
                                title: "Successfully",
                                text: "Task berhasil dihapus!",
                            });
                        })
                        .catch(function (err) {
                            console.log("err", err);
                        });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Dibatalkan",
                    });
                }
            });
        },
        removeStaff(user, taskId) {
            event.stopPropagation();
            const that = this;
            axios
                .post("updateStaff", {
                    task: "",
                    staff: user,
                    task_id: taskId,
                })
                .then(function (response) {
                    // console.log("resp", response);
                    that.splitAxios(response.data.original);
                    Vue.$toast.success("Staff Moved Successfully");
                })
                .catch(function (err) {
                    console.log("err", err);
                });
            console.log("staffcheck", user);
        },
        generateSppd() {
            event.stopPropagation();
        },
        splitData() {
            console.log("body1", this.todos);
            this.todos.forEach((s) => {
                var todo = {
                    id: s.id,
                    name: s.name,
                    staffs: JSON.parse(s.staff),
                    status: s.status,
                    description: s.description,
                    created_at: s.created_at,
                    updated: s.updated_at,
                    start: s.start,
                    dasar: JSON.parse(s.spt_id),
                    tipe_dinas: s.tipe_dinas,
                    kendaraan: s.kendaraan,
                    start_do: s.start_do,
                    finish_do: s.finish_do,
                    mulai_sppd: s.mulai_sppd,
                    selesai_sppd: s.selesai_sppd,
                    instansi: JSON.parse(s.attribute)
                        .instansi_pembebanan_anggaran,
                    kota_berangkat: JSON.parse(s.attribute).kota_berangkat,
                    kota_tujuan: JSON.parse(s.attribute).kota_tujuan,
                    mata_anggaran: JSON.parse(s.attribute).mata_anggaran,
                    keterangan: JSON.parse(s.attribute).keterangan,
                };
                this.newTodos.push(todo);
            });

            this.staffs.forEach((s) => {
                this.newStaff.push(s);
                localStorage.setItem('stafSkpdLain', JSON.stringify(this.newStaff));
            });

            console.log("staff-body", this.staffs);

            this.doing.forEach((s) => {
                var todo = {
                    id: s.id,
                    name: s.name,
                    staffs: JSON.parse(s.staff),
                    status: s.status,
                    description: s.description,
                    created_at: s.created_at,
                    updated: s.updated_at,
                    start: s.start,
                    dasar: JSON.parse(s.spt_id),
                    start_do: s.start_do,
                    finish_do: s.finish_do,
                    mulai_sppd: s.mulai_sppd,
                    selesai_sppd: s.selesai_sppd,
                    report: s.report,
                    tipe_dinas: s.tipe_dinas,
                    kendaraan: s.kendaraan,
                    instansi: JSON.parse(s.attribute)
                        .instansi_pembebanan_anggaran,
                    kota_berangkat: JSON.parse(s.attribute).kota_berangkat,
                    kota_tujuan: JSON.parse(s.attribute).kota_tujuan,
                    mata_anggaran: JSON.parse(s.attribute).mata_anggaran,
                    keterangan: JSON.parse(s.attribute).keterangan,
                };
                this.newDoing.push(todo);
            });

            this.done.forEach((s) => {
                var todo = {
                    id: s.id,
                    name: s.name,
                    staffs: JSON.parse(s.staff),
                    status: s.status,
                    description: s.description,
                    start: s.start,
                    dasar: JSON.parse(s.spt_id),
                    start_do: s.start_do,
                    finish_do: s.finish_do,
                    mulai_sppd: s.mulai_sppd,
                    selesai_sppd: s.selesai_sppd,
                    tipe_dinas: s.tipe_dinas,
                    report: s.report,
                    kendaraan: s.kendaraan,
                    instansi: JSON.parse(s.attribute)
                        .instansi_pembebanan_anggaran,
                    kota_berangkat: JSON.parse(s.attribute).kota_berangkat,
                    kota_tujuan: JSON.parse(s.attribute).kota_tujuan,
                    mata_anggaran: JSON.parse(s.attribute).mata_anggaran,
                    keterangan: JSON.parse(s.attribute).keterangan,
                };
                this.newDone.push(todo);
            });
        },
        resetTask() {
            this.newTodos = [];
            this.newDone = [];
            this.newDoing = [];
            this.newStaff = [];
        },
        splitAxios(data) {
            console.log("axios", data);
            this.resetTask();
            data.todo.forEach((s) => {
                var todo = {
                    id: s.id,
                    name: s.name,
                    staffs: JSON.parse(s.staff),
                    status: s.status,
                    description: s.description,
                    start: s.start,
                    dasar: JSON.parse(s.spt_id),
                    tipe_dinas: s.tipe_dinas,
                    kendaraan: s.kendaraan,
                    instansi: JSON.parse(s.attribute)
                        .instansi_pembebanan_anggaran,
                    kota_berangkat: JSON.parse(s.attribute).kota_berangkat,
                    kota_tujuan: JSON.parse(s.attribute).kota_tujuan,
                    mata_anggaran: JSON.parse(s.attribute).mata_anggaran,
                    keterangan: JSON.parse(s.attribute).keterangan,
                    mulai_sppd: s.mulai_sppd,
                    selesai_sppd: s.selesai_sppd,
                };
                this.newTodos.push(todo);
            });

            data.staffs.forEach((s) => {
                this.newStaff.push(s);
                localStorage.setItem('stafSkpdLain', this.newStaff);
            });

            data.doing.forEach((s) => {
                var todo = {
                    id: s.id,
                    name: s.name,
                    staffs: JSON.parse(s.staff),
                    status: s.status,
                    start: s.start,
                    description: s.description,
                    dasar: JSON.parse(s.spt_id),
                    start_do: s.start_do,
                    finish_do: s.finish_do,
                    report: s.report,
                    tipe_dinas: s.tipe_dinas,
                    kendaraan: s.kendaraan,
                    instansi: JSON.parse(s.attribute)
                        .instansi_pembebanan_anggaran,
                    kota_berangkat: JSON.parse(s.attribute).kota_berangkat,
                    kota_tujuan: JSON.parse(s.attribute).kota_tujuan,
                    mata_anggaran: JSON.parse(s.attribute).mata_anggaran,
                    keterangan: JSON.parse(s.attribute).keterangan,
                    mulai_sppd: s.mulai_sppd,
                    selesai_sppd: s.selesai_sppd,
                };
                this.newDoing.push(todo);
            });

            data.done.forEach((s) => {
                var todo = {
                    id: s.id,
                    name: s.name,
                    staffs: JSON.parse(s.staff),
                    status: s.status,
                    description: s.description,
                    start: s.start,
                    dasar: JSON.parse(s.spt_id),
                    start_do: s.start_do,
                    finish_do: s.finish_do,
                    report: s.report,
                    tipe_dinas: s.tipe_dinas,
                    kendaraan: s.kendaraan,
                    instansi: JSON.parse(s.attribute)
                        .instansi_pembebanan_anggaran,
                    kota_berangkat: JSON.parse(s.attribute).kota_berangkat,
                    kota_tujuan: JSON.parse(s.attribute).kota_tujuan,
                    mata_anggaran: JSON.parse(s.attribute).mata_anggaran,
                    keterangan: JSON.parse(s.attribute).keterangan,
                    mulai_sppd: s.mulai_sppd,
                    selesai_sppd: s.selesai_sppd,
                };
                this.newDone.push(todo);
            });
        },
        hideModalTask() {
            $("#modalTask").modal("hide");
            $("#modal_done").modal("hide");
            console.log("called");
        },
        //add new tasks method
        add: function () {
            if (this.newTask) {
                let data = this;
                this.todos.push({ name: this.newTask, staffs: [] });
                axios
                    .post("/postTodo", {
                        title: this.newTask,
                        status: "todo",
                        saffs: [],
                    })
                    .then(function (response) {
                        data.splitAxios(response.data.original);
                        console.log("resp", response);
                    })
                    .catch(function (err) {
                        console.log("err", err);
                    });
                this.newTask = "";
            }
        },
        checkMove: function (evt) {
            var status = evt.to.id;
            var id = evt.draggedContext.element.id;
            console.log("moved", evt);
            if (status == done) {
            }
            const that = this;

            axios
                .post("changeStatus", {
                    id: id,
                    status: status,
                })
                .then(function (response) {
                    console.log("resp", response.data);
                    that.splitAxios(response.data.original);
                    Vue.$toast.success("Task Berhasil dipindahkan!");
                })
                .catch(function (err) {
                    console.log("err", err);
                });
            this.status = status;
        },
    },
};
</script>

<style lang="scss">
/* light stylings for the kanban columns */
.staff-name {
    font-size: 12px;
    align-items: center;
    width: 100%;
    text-transform: capitalize;
}
.card-list {
    margin-right: 20px;
    background-color: #efefef9c;
    border-radius: 4px;
    transition: 0.5s;
    box-shadow: 0 1px 1px rgb(0 0 0 / 12%), 0 1px 1px rgb(0 0 0 / 24%);
}
.kanban-column {
    min-height: 100px;
}
.overflow-hidden {
    overflow: hidden;
}
.list-group-staff {
    transition: 0.5s;
}
.list-group-staff:hover {
    transform: translate(-5px, 0);
}
.list-group-item {
    margin-bottom: 5px;
    cursor: pointer;
    transition: 0.5s;
    border: unset;
    border-radius: 4px;
    h6 {
        font-family: "Acme", sans-serif;
    }
    .card-headers {
        margin: -6px 0 10px -13px;
        background: #dedede;
        width: 111%;
        padding-left: 10px;
    }
    .description {
        border-radius: 5px;
        background-color: #efefef;
        line-height: 1.2;
        padding: 0 5px 5px 5px;
        max-height: 45px;
        overflow: hidden;
        border-radius: 5px;
        transition: 1s;
        span {
            font-size: 10px;
            font-family: "Noto Serif Gujarati", serif;
        }
    }
}
.avatar {
    border-radius: 50%;
    padding: 3px;
    overflow: hidden;
    background: #ddd;
    object-position: 50px 30px;
}
.content-header {
    h6 {
        display: -webkit-box;
        max-width: 100%;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
}
.btn-remove {
    font-size: 10px;
    right: -7px;
    top: -7px;
    z-index: 9;
    i {
        font-size: 10px;
    }
}
.btn-print {
    font-size: 10px;
    right: 22px;
    top: -7px;
    z-index: 9;
    i {
        font-size: 10px;
    }
}
.delete-btn {
    opacity: 0;
    transition: 0.6s;
}

.list-group-item:hover .delete-btn {
    opacity: 1;
    i {
        color: red;
    }
}

.description:hover {
    height: 85px;
    max-height: 85px;
}
.created-at {
    span.badge {
        font-size: 10px;
    }
}
.loader {
    position: absolute;
    top: 30vh;
    z-index: 99;
}

.task-repo {
    background: #d4d4d491;
}

.input-group {
    #input-2 {
        border-right: none;
    }
    span button {
        border-radius: 0 10px 10px 0;
    }
}

.staff-list-stack {
    margin-left: -23px;
}
.staff-list-stack:first-child {
    margin-left: 0;
}
.badge-tipe {
    position: absolute;
    top: -12px;
    right: -6px;
}
</style>
