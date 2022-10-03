<template>
    <div class="container">
        <Header></Header>
        <div class="nav-menu d-flex justify-content-end">
            <b-dropdown id="dropdown-1" class="m-md-2 d-flex" size="sm">
                <div
                    slot="button-content"
                    class="d-flex align-items-center text-capitalize img-dropdown"
                >
                    <img height="20" src="img/user.png" />Welcome,
                    {{ user.name }} <i class="fas fa-caret-down ms-2"></i>
                </div>
                <b-dropdown-item>First Action</b-dropdown-item>
                <b-dropdown-item>Second Action</b-dropdown-item>
                <b-dropdown-item>Third Action</b-dropdown-item>
                <b-dropdown-divider></b-dropdown-divider>
                <b-dropdown-item active>Active action</b-dropdown-item>
                <b-dropdown-item disabled>Disabled action</b-dropdown-item>
                <form method="POST" action="/logout">
                    <input type="hidden" name="_token" :value="csrf" />
                    <button
                        type="submit"
                        class="dropdown-item"
                        data-bs-toggle="tooltip"
                        title="Keluar">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </b-dropdown>
        </div>
        <Body
            :todos="todos"
            :doing="doing"
            :done="done"
            :staffs="staffs"
            :role="role"
        ></Body>
    </div>
</template>

<script>
import Body from "./body";
import Header from "./headers";
export default {
    data() {
        return {
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
    },
    components: {
        Body,
        Header,
    },
    mounted() {
        this.role = this.user.role;
        console.log("Component mounted.", this.user);
    },
};
</script>
<style lang="scss">
.dropdown-toggle::after {
    display: none;
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
