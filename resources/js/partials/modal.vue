<template>
    <!-- Modal -->
    <div class="modal-list">
        <!-- Modal Task -->
        <div
        v-if="status !== 'done'"
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
                                ><i
                                    class="fa-solid fa-spinner text-light me-2"
                                ></i
                                ><span>{{ newStat }}</span></label
                            >
                            <h5
                                class="modal-title w-100"
                                id="exampleModalLabel"
                            >
                                <i class="fa-regular fa-window-maximize"></i>
                                <input
                                    v-if="taskData.status !== 'done'"
                                    type="text"
                                    class="form-control header"
                                    id="name"
                                    placeholder="Judul Task"
                                    name="name"
                                    v-model="taskData.name"
                                />
                                <input
                                    v-else
                                    type="text"
                                    class="form-control header cursor-block"
                                    id="name"
                                    disabled
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
                                title="ASN Terkait"
                                icon="fa-solid fa-users"
                            ></LabelTitle>
                            <div class="mb-3 input-text">
                                <div class="" v-if="newStaff.length < 1">
                                    <span class="badge rounded-pill bg-danger"
                                        >Belum ada ASN dipilih!</span
                                    >
                                </div>
                                <div
                                    class="avatar-list flex-row row mt-2"
                                    v-else
                                >
                                    <div
                                        v-for="s in newStaff"
                                        class="avatar-card d-flex col-12 col-md-6 align-items-center"
                                        :key="s.nip_terkait"
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
                                            s.nama
                                        }}</label>
                                    </div>
                                </div>
                                <v-select
                                    class="mt-2"
                                    :options="options"
                                    multiple
                                    v-model="newStaff"
                                    v-on:input="getId"
                                    label="nama"
                                    placeholder="Pilih Staff"
                                    :disabled="taskData.status == 'done'"
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
                                    :disabled="
                                        taskData.status == 'doing' ||
                                        taskData.status == 'done'
                                    "
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
                                    v-if="taskData.status == 'todo'"
                                    class="form-control"
                                    id="description"
                                    rows="5"
                                    name="description"
                                    v-model="taskData.description"
                                ></textarea>
                                <textarea
                                    v-else
                                    disabled
                                    class="form-control cursor-block"
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
                                    v-if="taskData.status == 'todo'"
                                    type="date"
                                    v-model="start"
                                    name="start"
                                    class="form-control"
                                />
                                <input
                                    v-else
                                    type="date"
                                    v-model="start"
                                    name="start"
                                    class="form-control disable cursor-block"
                                    :disabled="taskData.status == 'doing'"
                                />
                            </div>
                            <LabelTitle
                                v-if="status == 'doing' || status == 'done'"
                                title="Mulai mengerjakan"
                                icon="fa-solid fa-stopwatch"
                            ></LabelTitle>
                            <div
                                class="mb-3 input-text"
                                v-if="status == 'doing'"
                            >
                                <datetime
                                    class="form-control"
                                    type="datetime"
                                    v-model="start_on"
                                    use24-hour
                                ></datetime>
                            </div>
                            <div
                                class="mb-3 input-text"
                                v-if="status == 'done'"
                            >
                                <input
                                    class="form-control cursor-block"
                                    type="text"
                                    disabled
                                    v-model="start_on"
                                    use24-hour
                                />
                            </div>
                            <LabelTitle
                                v-if="status == 'doing' || status == 'done'"
                                title="Selesai mengerjakan"
                                icon="fa-solid fa-stopwatch"
                            ></LabelTitle>
                            <div
                                class="mb-3 input-text"
                                v-if="status == 'doing'"
                            >
                                <datetime
                                    class="form-control"
                                    type="datetime"
                                    v-model="finish_on"
                                    use24-hour
                                ></datetime>
                            </div>

                            <div
                                class="mb-3 input-text"
                                v-if="taskData.status == 'done'"
                            >
                                <datetime
                                    class="form-control"
                                    type="datetime"
                                    v-model="finish_on"
                                    use24-hour
                                ></datetime>
                            </div>

                            <div
                                class="mb-5"
                                v-if="status == 'doing' || status == 'done'"
                            >
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
                                    ref="fileReport"
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
                                        <p>Report:</p>
                                        <template v-if="preview">
                                            <img
                                                :src="preview"
                                                class="img-fluid"
                                            />
                                            <p class="mb-0">
                                                file name: {{ image.name }}
                                            </p>
                                            <p class="mb-0">
                                                size:
                                                {{
                                                    Math.round(
                                                        (image.size /
                                                            1024 /
                                                            1024) *
                                                            100
                                                    ) / 100
                                                }}MB
                                            </p>
                                        </template>
                                        <template v-else>
                                            <img
                                                :src="taskData.report"
                                                class="img-fluid"
                                            />
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
                                <label v-if="status == 'todo'"
                                    >Mulai Task</label
                                >
                                <label v-else>Selesaikan Task</label>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Done -->
        <div class="modal bg-white fade" tabindex="-1" id="modal_done">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content shadow-none">
                    <div class="modal-header mt-6">
                        <label
                            class="label-row bg-success text-capitalize text-light fw-bold"
                            ><i class="fa-solid fa-check text-light me-2"></i
                            ><span>{{ newStat }}</span></label
                        >
                        <h5 class="modal-title done">
                            <i class="fa-regular fa-window-maximize me-2"></i>
                            <span class="fw-bold text-capitalize">
                                {{ taskData.name }}
                            </span>
                        </h5>

                        <!--begin::Close-->
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body pt-1" style="overflow: unset">
                        <div class="container">
                            <div class="row justify-content-end mb-5">
                                <div
                                    class="d-flex flex-wrap justify-content-end"
                                >
                                    <div
                                        class="border border-gray-300 shadow-sm border-dashed rounded min-w-125px py-3 px-4 me-6"
                                    >
                                        <!--begin::Label-->
                                        <div
                                            class="fw-semibold fs-6 text-gray-400"
                                        >
                                            Mulai
                                        </div>
                                        <!--end::Label-->
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="fs-4 fw-bold text-success"
                                            >
                                                {{ taskData.start | moment }}
                                            </div>
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                    <div
                                        class="border border-gray-300 shadow-sm border-dashed rounded min-w-125px py-3 px-4 me-6"
                                    >
                                        <!--begin::Label-->
                                        <div
                                            class="fw-semibold fs-6 text-gray-400"
                                        >
                                            Dikerjakan pada:
                                        </div>
                                        <!--end::Label-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-4 fw-bold text-info">
                                                {{ taskData.start_do | moment }}
                                            </div>
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                    <div
                                        class="border border-gray-300 shadow-sm border-dashed rounded min-w-125px py-3 px-4 me-6"
                                    >
                                        <!--begin::Label-->
                                        <div
                                            class="fw-semibold fs-6 text-gray-400"
                                        >
                                            Selesai pada :
                                        </div>
                                        <!--end::Label-->
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="fs-4 fw-bold text-warning"
                                            >
                                                {{
                                                    taskData.finish_do | moment
                                                }}
                                            </div>
                                        </div>
                                        <!--end::Number-->
                                    </div>
                                    <!--end::Stat-->
                                </div>
                            </div>
                            <div class="mb-15">
                                <!--begin::Title-->
                                <h4 class="fs-2x text-gray-800 w-bolder mb-6">
                                    Deskripsi Task
                                </h4>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <p
                                    class="fw-semibold ms-2 fs-4 text-gray-600 mb-2"
                                >
                                    {{ taskData.description }}
                                </p>
                                <!--end::Text-->
                            </div>
                            <div class="card mb-10 shadow-sm">
                                <!--begin::Header-->
                                <div class="card-header border-0 pt-5">
                                    <h3
                                        class="card-title align-items-start flex-column"
                                    >
                                        <span
                                            class="card-label fw-bold fs-3 mb-1"
                                            >ASN Terkait</span
                                        >
                                    </h3>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-3">
                                    <!--begin::Table container-->
                                    <div class="row">
                                        <div
                                            class="col-md-4"
                                            v-for="s in newStaff"
                                            :key="s.nip_terkait"
                                        >
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div
                                                        class="symbol symbol-50px me-2"
                                                    >
                                                        <span
                                                            class="symbol-label"
                                                        >
                                                            <img
                                                                :src="s.foto"
                                                                @error="
                                                                    onErrorImg
                                                                "
                                                                class="h-75 align-self-end"
                                                                alt=""
                                                            />
                                                        </span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-10 d-flex flex-column align-items-left justify-content-center"
                                                >
                                                    <a
                                                        href="#"
                                                        class="text-dark fw-bold text-hover-primary mb-1 fs-6"
                                                        >{{ s.nama }}</a
                                                    >
                                                    <span
                                                        class="text-muted fw-semibold d-block"
                                                        >{{ s.id }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>

                            <div class="card mb-10 shadow-sm">
                                <div class="card-header border-0 pt-5">
                                    <h3
                                        class="card-title align-items-start flex-column"
                                    >
                                        <div
                                            class="card-label fw-bold fs-3 mb-1"
                                        >
                                            Laporan Task
                                        </div>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 col-md-12">
                                            <form
                                                @submit="formSubmit"
                                                enctype="multipart/form-data"
                                            >
                                                <input
                                                    type="hidden"
                                                    name="_token"
                                                    :value="token"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    :value="taskData.id"
                                                />
                                                <div
                                                    class="card card-flush py-4"
                                                >
                                                    <div
                                                        class="card-body text-center py-0"
                                                    >
                                                        <!--begin::Image input-->
                                                        <div
                                                            class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                                                            data-kt-image-input="true"
                                                        >
                                                            <!--begin::Preview existing avatar-->
                                                            <div
                                                                class="image-input-wrapper w-500px p-2"
                                                            >
                                                                <img
                                                                    v-if="preview"
                                                                    :src="preview"
                                                                    alt=""
                                                                    height="auto"
                                                                    width="100%"
                                                                />
                                                                <img
                                                                    v-else
                                                                    :src="
                                                                        taskData.report
                                                                    "
                                                                    alt=""
                                                                    height="auto"
                                                                    width="100%"
                                                                />
                                                            </div>
                                                            <!--end::Preview existing avatar-->
                                                            <!--begin::Label-->
                                                            <label
                                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                data-kt-image-input-action="change"
                                                                data-bs-toggle="tooltip"
                                                                title="Ganti laporan"
                                                                aria-label="Change avatar"
                                                                data-kt-initialized="1"
                                                            >
                                                                <i
                                                                    class="bi bi-pencil-fill fs-7"
                                                                ></i>
                                                                <!--begin::Inputs-->
                                                                <input
                                                                    type="file"
                                                                    name="avatar"
                                                                    @change="onFileChange"
                                                                    accept=".png, .jpg, .jpeg"
                                                                />
                                                                <input
                                                                    type="hidden"
                                                                    name="avatar_remove"
                                                                />
                                                                <!--end::Inputs-->
                                                            </label>
                                                            <!--end::Label-->
                                                            <!--begin::Cancel-->
                                                            <span
                                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                data-kt-image-input-action="cancel"
                                                                data-bs-toggle="tooltip"
                                                                title="Hapus Laporan"
                                                                aria-label="Cancel avatar"
                                                                data-kt-initialized="1"
                                                            >
                                                                <i
                                                                    class="bi bi-x fs-2"
                                                                ></i>
                                                            </span>
                                                            <!--end::Cancel-->
                                                            <!--begin::Remove-->
                                                            <span
                                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                                data-kt-image-input-action="remove"
                                                                data-bs-toggle="tooltip"
                                                                aria-label="Remove avatar"
                                                                data-kt-initialized="1"
                                                            >
                                                                <i
                                                                    class="bi bi-x fs-2"
                                                                ></i>
                                                            </span>
                                                            <!--end::Remove-->
                                                        </div>
                                                        <button v-if="preview" data-bas-toggle="tooltip" title="Simpan Report" type="submit" class="btn btn-icon btn-success rounded"><i class="fa-solid fa-save"></i></button>
                                                    </div>
                                                    <!--end::Card body-->
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dasar SPT -->
                            <div class="card mb-10 shadow-sm">
                                <!--begin::Header-->
                                <div class="card-header border-0 pt-5">
                                    <h3
                                        class="card-title align-items-start flex-column"
                                    >
                                        <span
                                            class="card-label fw-bold fs-3 mb-1"
                                            >Dasar Surat Perintah Tugas</span
                                        >
                                    </h3>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-3">
                                    <!--begin::Table container-->
                                    <div class="row">
                                        <div
                                            class="col-md-4"
                                            v-for="s in dasarSpt"
                                            :key="s.id"
                                        >
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div
                                                        class="symbol symbol-50px me-2"
                                                    >
                                                        <span
                                                            class="symbol-label"
                                                        >
                                                            <i
                                                                class="fa-solid fa-book"
                                                            ></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-10 d-flex flex-column align-items-left justify-content-center"
                                                >
                                                    <a
                                                        href="#"
                                                        class="text-dark fw-bold text-hover-primary mb-1 fs-6"
                                                        >{{ s.dasar }}</a
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >
                            <i class="fa-solid fa-times"></i>
                            Tutup
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click.prevent="mulaiTask(taskData.id, 'todo')"
                        >
                            <i class="fa-solid fa-chevron-left"></i> Kembalikan
                            ke Doing
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LabelTitle from "./part/label-title";
import vSelect from "vue-select";
import { Datetime } from "vue-datetime";
import moment from "moment";
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
        resetPreview: String,
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
                    contentType: false,
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
    filters: {
        moment: function (date) {
            return moment(date).format("D MMMM YYYY");
        },
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
            if (this.status == "doing" || this.status == "done") {
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
            this.preview = null;
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
        moment: function (date) {
            return moment(date);
        },
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
            console.log("filtered", this.options);
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
        updateReport(){
            console.log('report update')
        },
        formSubmit(e) {
            e.preventDefault();
            let that = this;

            var formData = new FormData();
            var id = this.taskData.id;
            var name = this.taskData.name;
            var status = this.taskData.status;
            var description = this.taskData.description;
            var staf = JSON.stringify(this.newStaff);
            var dasar = JSON.stringify(this.dasarSpt);
            var start = this.start;
            var start_on = this.start_on;
            var finish_on = this.finish_on;
            var file = this.file;
            var fileName = this.fileName;

            if (dasar == undefined) {
                var dasar = [];
            }

            formData.append("id", id);
            formData.append("name", name);
            formData.append("description", description);
            formData.append("staf", staf);
            formData.append("dasar", dasar);
            formData.append("start", start);
            formData.append("status", status);
            formData.append("start_on", start_on);
            formData.append("finish_on", finish_on);
            formData.append("file", file);

            console.log("file", file);

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
                } else if (file == null || file == "") {
                    Vue.$toast.warning(
                        "Mohon upload foto atau dokumen laporan!"
                    );
                } else {
                    axios
                        .post("/updateTask", formData, this.config)
                        .then(function (response) {
                            that.sembunyi();
                            that.$parent.splitAxios(response.data.original);
                            Vue.$toast.success("Task Updated Successfully");
                        })
                        .catch(function (err) {
                            Vue.$toast.error(err);
                        });
                }
            }else if(this.status == 'done'){
                axios
                        .post("/updateTask", formData, this.config)
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
.modal-title.done {
    i {
        font-size: 2rem;
    }
    span {
        font-size: 2rem;
    }
}

.image-input-wrapper {
    height: unset;
    background-position-x: 1000px;
}
.btn.btn-icon.rounded{
    border-radius: 50% !important;
    position: absolute;
    bottom: 20px;
    i{
        font-size: 24px;
    }
}
</style>
