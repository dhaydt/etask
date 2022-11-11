<template>
    <div class="addStaff">
        <div
            class="modal fade"
            id="addStaffModal"
            data-bs-backdrop="static"
            data-bs-keyboard="false"
            tabindex="-1"
        >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header position-relative">
                        <div class="title">
                            <h5 class="modal-title" id="staticBackdropLabel">
                                ASN Terkait
                            </h5>
                        </div>
                        <div
                            class="skpd d-flex flex-row"
                            style="min-width: 40%"
                        >
                            <div class="skpd-label">
                                <label for="">SKPD</label>
                            </div>
                            <v-select
                                class="mt-2 text-capitalize w-100 me-2"
                                :options="listSkpd"
                                v-model="skpd"
                                label="nama"
                                :placeholder="`Pilih dari SKPD Lain`"
                            ></v-select>
                        </div>

                        <div class="close-btn">
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="card-body py-3">
                            <!--begin::Accordion-->
                            <div class="accordion" id="kt_accordion_1">
                                <div class="accordion-item">
                                    <h2
                                        class="accordion-header"
                                        id="kt_accordion_1_header_2"
                                    >
                                        <button
                                            class="accordion-button fs-4 fw-semibold collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#kt_accordion_1_body_2"
                                            aria-expanded="false"
                                            aria-controls="kt_accordion_1_body_2"
                                        >
                                            <span
                                                class="svg-icon toggle-off svg-icon-1 me-2"
                                            >
                                                <svg
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <rect
                                                        opacity="0.3"
                                                        x="2"
                                                        y="2"
                                                        width="20"
                                                        height="20"
                                                        rx="5"
                                                        fill="currentColor"
                                                    ></rect>
                                                    <rect
                                                        x="10.8891"
                                                        y="17.8033"
                                                        width="12"
                                                        height="2"
                                                        rx="1"
                                                        transform="rotate(-90 10.8891 17.8033)"
                                                        fill="currentColor"
                                                    ></rect>
                                                    <rect
                                                        x="6.01041"
                                                        y="10.9247"
                                                        width="12"
                                                        height="2"
                                                        rx="1"
                                                        fill="currentColor"
                                                    ></rect>
                                                </svg>
                                            </span>
                                            Masyarakat
                                        </button>
                                    </h2>
                                    <div
                                        id="kt_accordion_1_body_2"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="kt_accordion_1_header_2"
                                        data-bs-parent="#kt_accordion_1"
                                    >
                                        <div class="accordion-body">
                                            <div class="accordion-body">
                                                <!--begin::Input group-->
                                                <div class="input-group mb-5">
                                                    <span
                                                        class="input-group-text"
                                                        id="basic-addon1"
                                                        >NIK</span
                                                    >
                                                    <input
                                                        type="number"
                                                        class="form-control"
                                                        placeholder="Nomor Induk Keluarga"
                                                        aria-label="nik"
                                                        v-model="nik_warga"
                                                        aria-describedby="basic-addon1"
                                                    />
                                                </div>
                                                <!--end::Input group-->
                                                <div class="input-group mb-5">
                                                    <span
                                                        class="input-group-text"
                                                        id="basic-addon1"
                                                        >Nama</span
                                                    >
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Nama"
                                                        v-model="nama_warga"
                                                        aria-label="nama"
                                                        aria-describedby="basic-addon1"
                                                    />
                                                </div>
                                                <div class="input-group mb-5">
                                                    <span
                                                        class="input-group-text"
                                                        id="basic-addon1"
                                                        >Profesi</span
                                                    >
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Profesi"
                                                        v-model="profesi_warga"
                                                        aria-label="profesi"
                                                        aria-describedby="basic-addon1"
                                                    />
                                                </div>
                                                <div
                                                    class="card-footer d-flex justify-content-end"
                                                >
                                                    <button
                                                        class="btn btn-sm btn-primary ms-auto btn-hover-rotate-end"
                                                        v-on:click="simpanWarga"
                                                    >
                                                        <i
                                                            class="fa-solid fa-save"
                                                        ></i>
                                                        Simpan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2
                                        class="accordion-header"
                                        id="kt_accordion_1_header_1"
                                    >
                                        <button
                                            class="accordion-button fs-4 fw-semibold"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#kt_accordion_1_body_1"
                                            aria-expanded="true"
                                            aria-controls="kt_accordion_1_body_1"
                                        >
                                            <span
                                                class="svg-icon toggle-off svg-icon-1 me-2"
                                            >
                                                <svg
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <rect
                                                        opacity="0.3"
                                                        x="2"
                                                        y="2"
                                                        width="20"
                                                        height="20"
                                                        rx="5"
                                                        fill="currentColor"
                                                    ></rect>
                                                    <rect
                                                        x="10.8891"
                                                        y="17.8033"
                                                        width="12"
                                                        height="2"
                                                        rx="1"
                                                        transform="rotate(-90 10.8891 17.8033)"
                                                        fill="currentColor"
                                                    ></rect>
                                                    <rect
                                                        x="6.01041"
                                                        y="10.9247"
                                                        width="12"
                                                        height="2"
                                                        rx="1"
                                                        fill="currentColor"
                                                    ></rect>
                                                </svg>
                                            </span>
                                            Pegawai SKPD
                                        </button>
                                    </h2>
                                    <div
                                        id="kt_accordion_1_body_1"
                                        class="accordion-collapse collapse show"
                                        aria-labelledby="kt_accordion_1_header_1"
                                        data-bs-parent="#kt_accordion_1"
                                    >
                                        <div class="tab-content">
                                            <div
                                                class="tab-pane fade show active"
                                                id="kt_table_widget_5_tab_1"
                                                role="tabpanel"
                                            >
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4"
                                                    >
                                                        <thead>
                                                            <tr
                                                                class="border-0"
                                                            >
                                                                <th
                                                                    class="p-0 w-50px"
                                                                ></th>
                                                                <th
                                                                    class="p-0 min-w-150px"
                                                                ></th>
                                                                <th
                                                                    class="p-0 min-w-140px"
                                                                ></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <Pegawai
                                                                v-for="dasar in dataPegawai"
                                                                :key="dasar.id"
                                                                :dasar="dasar"
                                                                :selected="
                                                                    selected
                                                                "
                                                            ></Pegawai>
                                                        </tbody>
                                                        <!--end::Table body-->
                                                    </table>
                                                </div>
                                                <!--end::Table-->
                                            </div>
                                            <!--end::Tap pane-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal SPT-->
        <div
            class="modal fade"
            id="addSptModal"
            data-bs-keyboard="false"
            data-bs-backdrop="static"
            tabindex="-1"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">
                            Dasar Perintah tugas
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <LabelTitle
                            class="mb-2"
                            title="Dasar"
                            icon="fa-solid fa-scale-balanced"
                        ></LabelTitle>
                        <div class="input-group mb-4 ms-2 input-group-solid">
                            <textarea
                                v-model="dasar"
                                class="form-control"
                                cols="30"
                                rows="7"
                            ></textarea>
                        </div>

                        <LabelTitle
                            class="mb-2"
                            title="Keterangan"
                            icon="fa-solid fa-note-sticky"
                        ></LabelTitle>
                        <div class="input-group mb-4 ms-2 input-group-solid">
                            <input
                                type="text"
                                v-model="keterangan"
                                class="form-control"
                                id="basic-url"
                                aria-describedby="basic-addon3"
                            />
                        </div>
                        <LabelTitle
                            class="mb-2"
                            title="Status"
                            icon="fa-solid fa-power-off"
                        ></LabelTitle>
                        <div
                            class="form-check form-switch form-check-custom form-check-solid ms-2 mb-4"
                        >
                            <input
                                class="form-check-input"
                                type="checkbox"
                                v-model="status"
                                id="flexSwitchDefault"
                            />
                            <label
                                class="form-check-label"
                                for="flexSwitchDefault"
                            >
                                <span
                                    v-if="status == true"
                                    class="badge badge-success"
                                    >Active</span
                                >
                                <span v-else class="badge badge-danger"
                                    >Non Active</span
                                >
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            :disabled="dasar == null"
                            class="btn btn-primary"
                            @click="addSpt()"
                        >
                            <div class="loading" v-if="loading">
                                <span
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                Menyimpan...
                            </div>
                            <label v-else>Simpan</label>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { stringify } from "querystring";
import LabelTitle from "./label-title";
import Pegawai from "./pegawai.vue";
import vSelect from "vue-select";
export default {
    components: { LabelTitle, Pegawai, vSelect },
    data() {
        return {
            nip: null,
            loading: false,
            dasar: null,
            keterangan: null,
            status: false,
            dataPegawai: [],
            namaSkpd: null,
            nik_warga: "",
            nama_warga: "",
            profesi_warga: "",
            selected: [],
            listSkpd: [],
            skpd: "",
        };
    },
    mounted() {
        this.$root.$on("addStaff", () => {
            this.addStaffModal();
        });
        this.$root.$on("addDasar", () => {
            this.addStaffModal();
        });
        this.$root.$on("updateDataPeg", () => {
            this.updateDataPegawai();
        });
        this.$root.$on("returnStaff", () => {
            this.returnStaff();
        });
        this.$root.$on("getListSkpd", () => {
            this.getListSkpd();
        });
    },
    watch: {
        skpd() {
            this.changeSkpd();
        },
    },
    methods: {
        simpanWarga() {
            this.$parent.toggleLoading(true);
            this.loading = true;

            var nik = this.nik_warga;
            var nama = this.nama_warga;
            var profesi = this.profesi_warga;

            console.log("form", nik, nama, profesi);
            var that = this;
            axios
                .post("addStaff", {
                    type: 'warga',
                    nik: nik,
                    nama: nama,
                    profesi: profesi
                })
                .then(function (response) {
                    console.log("resp", response["data"]);
                    if (response["data"]["code"] == 403) {
                        Vue.$toast.info(response["data"]["message"]);
                        $("#addStaffModal").modal("hide");
                        $(".modal-backdrop").remove();
                        that.loading = false;
                    }
                    if (response["data"]["code"] == 404) {
                        Vue.$toast.warning(response["data"]["message"]);
                        $("#addStaffModal").modal("hide");
                        $(".modal-backdrop").remove();
                        that.loading = false;
                    }
                    if (response["data"]["code"] == 200) {
                        Vue.$toast.success(response["data"]["message"]);
                        that.$parent.splitAxios(
                            response["data"]["data"].original
                        );
                        $("#addStaffModal").modal("hide");
                        $(".modal-backdrop").remove();
                        that.loading = false;
                    }
                    that.nik_warga = null;
                    that.nama_warga = null;
                    that.profesi_warga = null;
                })
                .catch(function (err) {
                    console.log("err", err);
                });
            that.$parent.toggleLoading(false);
        },
        changeSkpd() {
            var list = JSON.parse(localStorage.getItem("listSkpd"));
            var name = this.skpd;
            this.dataPegawai = list[name];
            // var sel = [];
            // var selected = JSON.parse(localStorage.getItem('stafSkpdLain'));
            // console.log('selected addstaff', selected)
            // this.dataPegawai.forEach(function(item, i){
            //     console.log('dp', item);
            //     var checkId = obj => obj == item.nip;
            //     if(selected.some(checkId) == true){
            //         sel.push(item);
            //     }
            // })
            // console.log('sel', sel);
        },
        getListSkpd() {
            var list = JSON.parse(localStorage.getItem("listSkpd"));
            console.log("list", list);
            this.listSkpd = Object.keys(list);
            this.skpd = localStorage.getItem("nama_skpd");
        },
        returnStaff() {
            console.log("returnStaff");
            this.dataPegawai = JSON.parse(
                localStorage.getItem("semuaPegawaiSkpd")
            );
            this.selected = JSON.parse(localStorage.getItem("asnTerdaftar"));
        },
        addStaffModal() {
            this.resetStaff();
            var selected = JSON.parse(localStorage.getItem("asnTerdaftar"));
            var allStaff = JSON.parse(localStorage.getItem("semuaPegawaiSkpd"));

            this.dataPegawai = allStaff;
            this.selected = selected;
            this.namaSkpd = allStaff[0].nama_skpd;
            console.log("dataPeg", this.dataPegawai);
            console.log("selected", this.selected);
            $("#addStaffModal").modal("show");
        },
        resetStaff() {
            this.selected = [];
            this.dataPegawai = [];
        },
        updateDataPegawai() {
            // this.dataPegawai = JSON.parse(localStorage.getItem('semuaPegawaiSkpd'));
            // this.selected = JSON.parse(localStorage.getItem('asnTerdaftar'));
        },
        getSkpd() {
            this.$parent.toggleLoading(true);
            var id_skpd = localStorage.getItem("id_skpd");
            var that = this;

            axios.get("pegawaiSkpd").then(function (resp) {
                if (resp.data.code == 200) {
                    var dataSkpd = resp.data.data;
                    var user = resp.data.user;
                    var selected = [];
                    dataSkpd.forEach(function (item, i) {
                        if (item.id_skpd == id_skpd) {
                            var newItem = {};
                            user.forEach(function (u, i) {
                                if (u.id.toString() !== item.nip) {
                                    console.log(
                                        "compare",
                                        u.id.toString(),
                                        item.nip
                                    );
                                    var status = {
                                        status: false,
                                    };
                                    newItem = { ...item, ...status };
                                } else {
                                    var status = {
                                        status: true,
                                    };
                                    newItem = { ...item, ...status };
                                }
                            });
                            selected.push(newItem);
                        }
                    });
                    that.namaSkpd = selected[0].nama_skpd;
                    console.log("selected", selected);
                    that.dataPegawai = selected;
                    $("#addStaffModal").modal("show");
                    that.$parent.toggleLoading(false);
                }
            });
        },
        addSpt() {
            this.$parent.toggleLoading(true);
            this.loading = true;
            var that = this;
            axios
                .post("addSpt", {
                    dasar: that.dasar,
                    keterangan: that.keterangan,
                    status: that.status,
                })
                .then(function (response) {
                    console.log("resp", response["data"]);

                    if (response["data"].code == 200) {
                        $("#addSptModal").modal("hide");
                        $(".modal-backdrop").remove();
                        that.dasar = null;
                        that.keterangan = null;
                        that.status = false;
                        Vue.$toast.success(response["data"].message);
                    }
                })
                .catch(function (err) {
                    console.log("err", err);
                });
            that.loading = false;
            that.$parent.toggleLoading(false);
        },
        splitAxios(data) {
            this.$parent.splitAxios(data);
        },

        addStaff() {
            this.$parent.toggleLoading(true);
            this.loading = true;
            var that = this;
            axios
                .post("addStaff", {
                    nip: that.nip,
                })
                .then(function (response) {
                    console.log("resp", response["data"]);
                    if (response["data"]["code"] == 403) {
                        Vue.$toast.info(response["data"]["message"]);
                        $("#addStaffModal").modal("hide");
                        $(".modal-backdrop").remove();
                        that.loading = false;
                    }
                    if (response["data"]["code"] == 404) {
                        Vue.$toast.warning(response["data"]["message"]);
                        $("#addStaffModal").modal("hide");
                        $(".modal-backdrop").remove();
                        that.loading = false;
                    }
                    if (response["data"]["code"] == 200) {
                        Vue.$toast.success(response["data"]["message"]);
                        that.$parent.splitAxios(
                            response["data"]["data"].original
                        );
                        $("#addStaffModal").modal("hide");
                        $(".modal-backdrop").remove();
                        that.loading = false;
                    }
                    that.nip = null;
                })
                .catch(function (err) {
                    console.log("err", err);
                });
            that.$parent.toggleLoading(false);
        },
    },
};
</script>

<style lang="scss" scoped>
.skpd-label {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 6px;
    padding: 0 10px 1px 10px;
    border-radius: 4px;
    background: #cdcdcd;
    font-weight: 600;
    border: 1px solid #cdcdcd;
}
.close-btn {
    position: absolute;
    top: -13px;
    right: -18px;
    z-index: 2;
    background: red;
    border: 3px solid #fefefe;
    border-radius: 50%;
    justify-content: center;
    align-items: center;
    padding-right: 10px;
    padding-top: 2px;

    button {
        color: #fff;
        font-weight: 700;
    }
}
h5.modal-title {
    font-family: "Acme", sans-serif;
    font-size: 18px;
}
.addStaff {
    display: flex;
    justify-content: right;
    margin-top: -55px;
    height: 20px;

    .btnAdd {
        border-radius: 4px;
    }
}
</style>
