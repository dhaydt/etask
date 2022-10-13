<template>
    <div class="addStaff mb-1">
        <!-- <button
            type="button"
            class="btn btn-light-info btnAdd btn-hover-rotate-end me-2"
            data-bs-toggle="modal"
            data-bs-target="#addSptModal"
        >
            <i class="fas fa-plus"></i> Dasar SPT
        </button>
        <button
            type="button"
            class="btn btn-light-success btnAdd btn-hover-rotate-start"
            @click="addStaffModal()"
        >
            <i class="fas fa-plus"></i> ASN Terkait
        </button> -->

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
                        <h5 class="modal-title" id="staticBackdropLabel">
                            STAFF {{ namaSkpd }}
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
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
                                                    <th
                                                        class="p-0 min-w-110px"
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
export default {
    components: { LabelTitle, Pegawai },
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
        };
    },
    mounted(){
        this.$root.$on('addStaff', () => {
            this.addStaffModal();
        })
        this.$root.$on('addDasar', () => {
            this.addStaffModal();
        })
    },
    methods: {
        addStaffModal() {
            var selected = JSON.parse(localStorage.getItem("saved"));
            var allStaff = JSON.parse(localStorage.getItem("asn_skpd"));

            this.dataPegawai = allStaff;
            this.selected = selected;
            this.namaSkpd = allStaff[0].nama_skpd;
            $("#addStaffModal").modal("show");
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
    height: 40px;

    .btnAdd {
        border-radius: 4px;
    }
}
</style>
