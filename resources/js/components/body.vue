<template>
    <div class="container position-relative" style="margin-top: 50px">
        <div class="row pt-4 position-relative">
            <AddStaff></AddStaff>
            <div class="loader d-flex justify-content-center">
                <beat-loader :loading="loading" color="white" size="30px"></beat-loader>
            </div>
            <div class="col-3">
                <div class="p-2 bg-secondary text-dark fw-bold bg-hover-light-secondary card-list w-100 m-0">
                    <div class="list-header mb-2">
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
                            class="list-group-item text-capitalize"
                            v-for="element in newTodos"
                            :key="element.name"
                            @click="cardModal(element)"
                        >
                            <h6
                                class="mb-0 text-capitalize d-flex justify-content-between align-items-center"
                            >
                                {{ element.name }}
                                <button
                                    @click="removeTask(element.id)"
                                    class="btn delete-btn btn-sm text-danger"
                                    data-bs-toggle="tooltip"
                                    title="Hapus"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </h6>
                            <div
                                v-if="element.description"
                                class="description ps-2 mb-3"
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
                                @move="checkStaff"
                            >
                                <div
                                    class="list-group-staff d-flex card flex-row shadow-sm p-1 mb-1"
                                    v-for="staf in element.staffs"
                                    :key="staf.id"
                                >
                                    <button
                                        @click="
                                            removeStaff(staf.id, element.id)
                                        "
                                        class="btn btn-sm btn-danger btn-hover-scale p-0 btn-remove position-absolute text-light"
                                        data-bs-toggle="tooltip"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-times px-1 py-0"></i>
                                    </button>
                                    <div
                                        class="avatar me-2 text-capitalize position-relative"
                                        data-bs-toggle="tooltip"
                                        :title="staf.name"
                                    >
                                        <img
                                            height="25"
                                            src="img/user.png"
                                            alt=""
                                        />
                                    </div>
                                    <div
                                        class="staff-name d-flex justify-content-center"
                                    >
                                        <div class="staff-label">
                                            {{ staf.name }}
                                        </div>
                                    </div>
                                </div>
                            </draggable>
                            <div
                                class="created-at mt-3 d-flex justify-content-end"
                            >
                                <span v-if="element.start" class="badge rounded-pill badge-secondary">
                                    {{ element.start | moment }}
                                </span>

                                <span v-else class="badge rounded-pill badge-warning">
                                    Pengerjaan belum diatur
                                </span>

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
                                        <i class="fas fa-ellipsis-h"></i>
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
        ></Modal>
    </div>
</template>

<script>
import AddStaff from "../partials/part/addStaff";
import Modal from "../partials/modal";
import Staff from "../partials/staff";
import Done from "../partials/done";
import Doing from "../partials/doing";
import draggable from "vuedraggable";
import axios from "axios";
import moment from "moment";
import BeatLoader from 'vue-spinner/src/BeatLoader.vue'


export default {
    name: "e-task",
    components: {
        AddStaff,
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
                disabled: false,
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
            staffSingle: [],
            newTodos: [],
            newDoing: [],
            newDone: [],
            newStaff: [],
            newDasarList: [],
            taskTitle: null,
            taskDescription: null,
            taskStaff: [],
            taskData: [],
            newDasar: [],
            loading: false,
        };
    },
    mounted() {
        console.log('staffBody', this.staffs)
        this.splitData();
    },
    props: {
        todos: Array,
        doing: Array,
        done: Array,
        staffs: Array,
        role: String,
        dasar: Array
    },
    filters: {
        moment: function (date) {
            return moment(date).format("D MMMM YYYY");
        },
    },
    methods: {
        updateDasarStatus(data){
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
            console.log("delete", taskId);
            const that = this;
            axios
                .post("deleteTask", {
                    task_id: taskId,
                })
                .then(function (response) {
                    // console.log("resp", response);
                    that.splitAxios(response.data.original);
                    Vue.$toast.success("Task deleted Successfully");
                })
                .catch(function (err) {
                    console.log("err", err);
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
        splitData() {
            console.log('body1', this.todos);
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
                    dasar: JSON.parse(s.spt_id)
                };
                this.newTodos.push(todo);
            });

            this.staffs.forEach((s) => {
                this.newStaff.push(s);
            });

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
                    dasar: JSON.parse(s.spt_id)
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
                    dasar: JSON.parse(s.spt_id)
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
                    dasar: JSON.parse(s.spt_id)
                };
                this.newTodos.push(todo);
            });

            data.staffs.forEach((s) => {
                this.newStaff.push(s);
            });

            data.doing.forEach((s) => {
                var todo = {
                    id: s.id,
                    name: s.name,
                    staffs: JSON.parse(s.staff),
                    status: s.status,
                    start: s.start,
                    description: s.description,
                    dasar: JSON.parse(s.spt_id)
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
                    dasar: JSON.parse(s.spt_id)
                };
                this.newDone.push(todo);
            });
        },
        hideModalTask() {
            $("#modalTask").modal("hide");
            console.log("called");
        },
        cardModal(data) {
            var modalTask = new bootstrap.Modal(
                document.getElementById("modalTask"),
                {
                    keyboard: false,
                }
            );
            console.log("data modal", data);
            this.status = data.status;
            this.taskData = data;
            modalTask.show();
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
            console.log("moved", id);
            const that = this;

            axios
                .post("changeStatus", {
                    id: id,
                    status: status,
                })
                .then(function (response) {
                    console.log("resp", response.data);
                    that.splitAxios(response.data.original);
                    Vue.$toast.success("Task Moved Successfully");
                })
                .catch(function (err) {
                    console.log("err", err);
                });
            this.status = status;
        },
        checkStaff: function (evt) {
            // console.log("evt", evt);
            // var taskId = evt.to.id;
            // var from_id = evt.from.id;
            // var user = evt.draggedContext.element.id;
            // console.log(evt);
            // axios
            //     .post("updateStaff", {
            //         task: taskId,
            //         staff: user,
            //         task_id: from_id,
            //     })
            //     .then(function (response) {
            //         console.log("resp", response);
            //         Vue.$toast.success("Staff Moved Successfully");
            //     })
            //     .catch(function (err) {
            //         console.log("err", err);
            //     });
            // console.log("staffcheck", user);
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
    margin: 5px;
    margin-right: 20px;
    background-color: #efefef9c;
    border-radius: 4px;
    transition: .5s;
    box-shadow: 0 1px 1px rgb(0 0 0 / 12%), 0 1px 1px rgb(0 0 0 / 24%);
}
.kanban-column {
    min-height: 100px;
}
.list-group-item {
    margin-bottom: 5px;
    cursor: pointer;
    transition: 0.5s;
    h6 {
        font-family: "Acme", sans-serif;
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
            font-size: 12px;
            font-family: "Acme", sans-serif;
        }
    }
}
.avatar {
    border-radius: 50%;
    padding: 3px;
    background: #ddd;
}
.btn-remove {
    font-size: 10px;
    right: -7px;
    top: -7px;
    z-index: 9;
}
h6 .delete-btn {
    opacity: 0;
    transition: 0.6s;
}

.list-group-item:hover h6 .delete-btn {
    opacity: 1;
    i{
        color: red;
    }
}

.list-group-item:hover .description {
    height: 85px;
    max-height: 85px;
}
.created-at {
    span.badge {
        font-size: 10px;
    }
}
.loader{
    position: absolute;
    top: 30vh;
    z-index: 99;
}
</style>
