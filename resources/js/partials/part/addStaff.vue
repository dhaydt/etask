<template>
    <div class="addStaff mb-1">
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn btnAdd"
            data-bs-toggle="modal"
            data-bs-target="#addStaffModal"
        >
            <i class="fas fa-plus"></i> Staff
        </button>

        <!-- Modal -->
        <div
            class="modal fade"
            id="addStaffModal"
            data-bs-keyboard="false"
            tabindex="-1"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">
                            Tambah Staff Baru
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <label for="basic-url" class="form-label"
                            >Masukan NIP Staff</label
                        >
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon3"
                                ><i class="fas fa-user"></i
                            ></span>
                            <input
                                type="text"
                                v-model="nip"
                                class="form-control"
                                id="basic-url"
                                aria-describedby="basic-addon3"
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            :disabled="nip == null"
                            class="btn btn-primary"
                            @click="addStaff()"
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
export default {
    data() {
        return {
            nip: null,
            loading: false,
        };
    },
    methods: {
        addStaff() {
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
                        that.$parent.splitAxios(response["data"]["data"].original);
                        $("#addStaffModal").modal("hide");
                        $(".modal-backdrop").remove();
                        that.loading = false;
                    }
                    that.nip = null;
                })
                .catch(function (err) {
                    console.log("err", err);
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.addStaff {
    display: flex;
    justify-content: right;
    margin-top: -55px;
    height: 40px;

    .btnAdd {
        border-radius: 4px;
        background: rgba(209, 231, 221, 0.6705882353);
        transition: 0.5s;
    }

    .btnAdd:hover {
        scale: 1.2;
    }
}
</style>
