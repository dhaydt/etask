<template>
    <div class="main-body mb-4">
        <Header :users="users"></Header>
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
            users: [],
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };
    },
    props: {
        todos: Array,
        doing: Array,
        done: Array,
        staffs: Array | Object,
        user: Array | Object,
        dasar: Array,
        roles: Number,
        id_skpd: String | Number
    },
    components: {
    DasarSpt,
        Body,
        Header,
    },
    mounted() {
        this.role = String(this.roles);
        this.dasarSpt = this.dasar;
        this.users = this.user;
        localStorage.setItem('id_skpd', this.user.id_skpd);
        localStorage.setItem('role', this.role);
        localStorage.setItem('user', JSON.stringify(this.user));
    },
    methods:{
        reloadSpt(data){
            this.dasarSpt = data;
        },
        updateDasarStatus(data){
            this.dasarSpt = data.original.dasar;
        },
    }
};
</script>
<style lang="scss">
.dropdown-toggle::after {
    display: none;
}
nav{
    position: relative;
    z-index: 2;
}
.logout-btn{
    border: none;
    background-color: transparent;
}
.user-btn {
    z-index: 0;
    height: 30px;
    border-radius: 4px;
}

.nav-menu {
    height: 44px;
    margin-right: -6vw;
    max-width: 100%;
}
.img-dropdown img {
    border-radius: 50%;
    background: #fff;
    padding: 2px;
    margin-right: 5px;
}
</style>
