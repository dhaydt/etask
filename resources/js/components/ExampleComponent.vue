<template>
    <div class="container">
        <Header></Header>
        <div class="nav-menu d-flex justify-content-end align-items-center">
            <button
                type="button"
                class="btn btn-secondary rotate user-btn py-0 px-2"
                data-kt-menu-trigger="hover"
                data-kt-menu-placement="bottom-start"
                data-kt-menu-offset="0px, 5px"
            >
                <img height="20" class="me-2" src="img/user.png" />Welcome, {{ user.name }}
                <i class="fas fa-caret-down ms-2"></i>
            </button>

            <div
                class="menu menu-user menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                data-kt-menu="true"
            >
                <div class="menu-item px-3">
                    <a href="javascript:"  data-bs-toggle="modal" data-bs-target="#dasarSpt" class="menu-link px-3"> Dasar SPT </a>
                </div>
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3"> New Customer </a>
                </div>
                <div class="separator mb-3 opacity-75"></div>
                <div class="menu-item px-3">
                    <form method="POST" action="/logout">
                        <input type="hidden" name="_token" :value="csrf" />
                        <a
                            href="javascript:"
                            type="submit"
                            class="menu-link px-3 text-danger"
                        >
                            <i class="fa-solid fa-right-from-bracket me-2 text-danger"></i>
                            Keluar
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <Body
            :todos="todos"
            :doing="doing"
            :done="done"
            :staffs="staffs"
            :role="role"
            :dasar="dasarSpt"
        ></Body>
        <DasarSpt :dasar="dasarSpt"></DasarSpt>
    </div>
</template>

<script>
import DasarSpt from '../partials/part/dasarSpt'
import Body from "./body";
import Header from "./headers";
export default {
    data() {
        return {
            dasarSpt:[],
            role: null,
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };
    },
    props: {
        todos: Array,
        doing: Array,
        done: Array,
        staffs: Array,
        user: Object,
        dasar: Array,
    },
    components: {
    DasarSpt,
        Body,
        Header,
    },
    mounted() {
        this.role = this.user.role;
        this.dasarSpt = this.dasar;
        console.log("Component mounted.", this.user);
    },
    methods:{
        reloadSpt(data){
            this.dasarSpt = data;
        }
    }
};
</script>
<style lang="scss">
.dropdown-toggle::after {
    display: none;
}
.user-btn {
    z-index: 1;
    height: 36px;
}

.nav-menu {
    height: 44px;
    margin-right: -6vw;
}
.img-dropdown img {
    border-radius: 50%;
    background: #fff;
    padding: 2px;
    margin-right: 5px;
}
</style>
