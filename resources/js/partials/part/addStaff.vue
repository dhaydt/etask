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
                    <div class="modal-header">
                        <div class="title">
                            <h5 class="modal-title" id="staticBackdropLabel">
                                ASN Terkait
                            </h5>
                        </div>
                        <div class="skpd" style="min-width: 40%;">
                            <v-select
                                class="mt-2 text-capitalize w-100"
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
                                                <tr class="border-0">
                                                    <th class="p-0 w-50px"></th>
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
                                                    :selected="selected"
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
            selected: [],
            listSkpd: [],
            skpd: ''
        };
    },
    mounted(){
        this.$root.$on('addStaff', () => {
            this.addStaffModal();
        })
        this.$root.$on('addDasar', () => {
            this.addStaffModal();
        })
        this.$root.$on('updateDataPeg', () => {
            this.updateDataPegawai();
        })
        this.$root.$on('returnStaff', () => {
            this.returnStaff();
        })
        this.$root.$on('getListSkpd', () => {
            this.getListSkpd();
        })
    },
    watch:{
        skpd(){
            this.changeSkpd();
        }
    },
    methods: {
        changeSkpd(){
            var list = JSON.parse(localStorage.getItem('listSkpd'));
            var name = this.skpd;
            this.selected = JSON.parse(localStorage.getItem('stafSkpdLain'));
            this.dataPegawai = list[name];
            console.log('skpd', this.dataPegawai)
        },
        getListSkpd(){
            var list = JSON.parse(localStorage.getItem('listSkpd'));
            console.log('list', list);
            this.listSkpd = Object.keys(list);
            this.skpd = localStorage.getItem('nama_skpd');

        },
        returnStaff(){
            console.log('returnStaff');
            this.dataPegawai = JSON.parse(localStorage.getItem('semuaPegawaiSkpd'));
            this.selected = JSON.parse(localStorage.getItem('asnTerdaftar'));
        },
        addStaffModal() {
            this.resetStaff();
            var selected = JSON.parse(localStorage.getItem("asnTerdaftar"));
            var allStaff = JSON.parse(localStorage.getItem("semuaPegawaiSkpd"));

            this.dataPegawai = allStaff;
            this.selected = selected;
            this.namaSkpd = allStaff[0].nama_skpd;
            console.log('dataPeg', this.dataPegawai)
            console.log('selected', this.selected)
            $("#addStaffModal").modal("show");
        },
        resetStaff(){
            this.selected = [];
            this.dataPegawai = [];
        },
        updateDataPegawai(){
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
