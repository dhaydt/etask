<template>
    <div class="dasarSpt">
        <div
            class="modal fade"
            tabindex="-1"
            id="dasarSpt"
            data-bs-backdrop="static"
            data-bs-keyboard="false"
        >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header shadow-sm">
                        <h3 class="modal-title">Dasar Surat Perintah Tugas</h3>

                        <!--begin::Close-->
                        <div
                            class="btn btn-icon btn-sm btn-active-light-danger ms-2"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            <span class="fa-solid fa-times"></span>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div class="card-body pb-3">
                            <div class="row justify-content-end">
                                <div class="col-md-4 d-flex justify-content-end">
                                    <a href="/edit-spt" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Kelola SPT
                                    </a>
                                </div>
                            </div>
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
                                                    <!-- <th
                                                        class="p-0 min-w-50px"
                                                    ></th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <ContentDasar
                                                    v-for="dasar in dasarSpt"
                                                    :key="dasar.id"
                                                    :dasar="dasar"
                                                ></ContentDasar>
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
</template>

<script>
import ContentDasar from "./contentDasar";

export default {
    components: { ContentDasar },
    props: {
        dasar: Array,
    },
    data() {
        return {
            dasarSpt: [],
            newDasar: "",
            newKet: "",
        };
    },
    watch: {
        dasar() {
            this.updateDasar();
        },
    },
    methods: {
        editSpt(data) {},
        updateDasar() {
            this.dasarSpt = this.dasar;
        },
        updateDasarStatus(data) {
            this.$parent.updateDasarStatus(data);
        },
    },
};
</script>

<style lang="scss" scoped>
.modal-body{
    height: 75vh;
    overflow-y: scroll;
    margin-bottom: 10px;
}
</style>
