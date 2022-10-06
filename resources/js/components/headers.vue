<template>
    <nav class="navbar navbar-light">
        <div class="container-fluid h-100">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="img/etask.png" height="23" alt="" class="me-2" />
                E-Tasks
            </a>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            rule: null,
        };
    },
};
</script>

<style lang="scss" scoped>
.navbar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background-color: #775044;
    min-height: 44px;
    max-height: 44px;
    // overflow: hidden;
    padding-top: 2px;

    .logout-btn {
        border-radius: 50%;
        margin: 5px 10px 3px 0;
        padding: 2px 6px;
    }
    .container-fluid {
        transition: background-color 300ms;
        // background-color: #026aa7;
        border-bottom: 1px solid #ffffff29;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        // overflow: hidden;
        -webkit-backdrop-filter: blur(6px);
        backdrop-filter: blur(6px);
        max-height: 44px;
    }

    .navbar-brand {
        color: #fff;
        font-weight: 700;
        font-family: "Courgette", cursive;
    }
}
</style>
