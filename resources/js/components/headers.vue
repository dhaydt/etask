<template>
    <nav class="navbar navbar-light">
        <div class="container-fluid h-100">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="img/etask.png" height="23" alt="" class="me-2" />
                E-Tasks
            </a>
            <!-- <div class="d-flex align-items-center ms-1 ms-lg-3">
                <div
                    class="btn btn-color-gray-800 btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px position-relative"
                    data-kt-menu-trigger="click"
                    data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end"
                >
                    <span class="svg-icon svg-icon-2x">
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                fill="currentColor"
                            ></path>
                            <rect
                                opacity="0.3"
                                x="8"
                                y="3"
                                width="8"
                                height="8"
                                rx="4"
                                fill="currentColor"
                            ></rect>
                        </svg>
                    </span>
                </div>
                <div
                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true"
                    style=""
                >
                    <div class="menu-item px-3">
                        <div
                            class="menu-content d-flex align-items-center px-3"
                        >
                            <div class="symbol symbol-50px me-5">
                                <img
                                    alt="Logo"
                                    src="assets/media/avatars/300-1.jpg"
                                />
                            </div>
                            <div class="d-flex flex-column">
                                <div
                                    class="fw-bold d-flex align-items-center fs-5"
                                >
                                    Max Smith
                                    <span
                                        class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2"
                                        >Pro</span
                                    >
                                </div>
                                <a
                                    href="#"
                                    class="fw-semibold text-muted text-hover-primary fs-7"
                                    >max@kt.com</a
                                >
                            </div>
                        </div>
                    </div>
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5">
                        <a href="account/overview.html" class="menu-link px-5"
                            >My Profile</a
                        >
                    </div>
                    <div class="menu-item px-5">
                        <a
                            href="apps/projects/list.html"
                            class="menu-link px-5"
                        >
                            <span class="menu-text">My Projects</span>
                            <span class="menu-badge">
                                <span
                                    class="badge badge-light-danger badge-circle fw-bold fs-7"
                                    >3</span
                                >
                            </span>
                        </a>
                    </div>
                    <div
                        class="menu-item px-5"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start"
                    >
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title">My Subscription</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div
                            class="menu-sub menu-sub-dropdown w-175px py-4"
                            style=""
                        >
                            <div class="menu-item px-3">
                                <a
                                    href="account/referrals.html"
                                    class="menu-link px-5"
                                    >Referrals</a
                                >
                            </div>
                            <div class="menu-item px-3">
                                <a
                                    href="account/billing.html"
                                    class="menu-link px-5"
                                    >Billing</a
                                >
                            </div>
                            <div class="menu-item px-3">
                                <a
                                    href="account/statements.html"
                                    class="menu-link px-5"
                                    >Payments</a
                                >
                            </div>
                            <div class="menu-item px-3">
                                <a
                                    href="account/statements.html"
                                    class="menu-link d-flex flex-stack px-5"
                                    >Statements
                                    <i
                                        class="fas fa-exclamation-circle ms-2 fs-7"
                                        data-bs-toggle="tooltip"
                                        aria-label="View your statements"
                                        data-kt-initialized="1"
                                    ></i
                                ></a>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-3">
                                <div class="menu-content px-3">
                                    <label
                                        class="form-check form-switch form-check-custom form-check-solid"
                                    >
                                        <input
                                            class="form-check-input w-30px h-20px"
                                            type="checkbox"
                                            value="1"
                                            checked="checked"
                                            name="notifications"
                                        />
                                        <span
                                            class="form-check-label text-muted fs-7"
                                            >Notifications</span
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item px-5">
                        <a href="account/statements.html" class="menu-link px-5"
                            >My Statements</a
                        >
                    </div>
                    <div class="separator my-2"></div>
                    <div
                        class="menu-item px-5"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-start"
                    >
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative"
                                >Language
                                <span
                                    class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0"
                                    >English
                                    <img
                                        class="w-15px h-15px rounded-1 ms-2"
                                        src="assets/media/flags/united-states.svg"
                                        alt="" /></span
                            ></span>
                        </a>
                        <div class="menu-sub menu-sub-dropdown w-175px py-4">
                            <div class="menu-item px-3">
                                <a
                                    href="account/settings.html"
                                    class="menu-link d-flex px-5 active"
                                >
                                    <span class="symbol symbol-20px me-4">
                                        <img
                                            class="rounded-1"
                                            src="assets/media/flags/united-states.svg"
                                            alt=""
                                        /> </span
                                    >English</a
                                >
                            </div>
                            <div class="menu-item px-3">
                                <a
                                    href="account/settings.html"
                                    class="menu-link d-flex px-5"
                                >
                                    <span class="symbol symbol-20px me-4">
                                        <img
                                            class="rounded-1"
                                            src="assets/media/flags/spain.svg"
                                            alt=""
                                        /> </span
                                    >Spanish</a
                                >
                            </div>
                            <div class="menu-item px-3">
                                <a
                                    href="account/settings.html"
                                    class="menu-link d-flex px-5"
                                >
                                    <span class="symbol symbol-20px me-4">
                                        <img
                                            class="rounded-1"
                                            src="assets/media/flags/germany.svg"
                                            alt=""
                                        /> </span
                                    >German</a
                                >
                            </div>
                            <div class="menu-item px-3">
                                <a
                                    href="account/settings.html"
                                    class="menu-link d-flex px-5"
                                >
                                    <span class="symbol symbol-20px me-4">
                                        <img
                                            class="rounded-1"
                                            src="assets/media/flags/japan.svg"
                                            alt=""
                                        /> </span
                                    >Japanese</a
                                >
                            </div>
                            <div class="menu-item px-3">
                                <a
                                    href="account/settings.html"
                                    class="menu-link d-flex px-5"
                                >
                                    <span class="symbol symbol-20px me-4">
                                        <img
                                            class="rounded-1"
                                            src="assets/media/flags/france.svg"
                                            alt=""
                                        /> </span
                                    >French</a
                                >
                            </div>
                        </div>
                    </div>
                    <div class="menu-item px-5 my-1">
                        <a href="account/settings.html" class="menu-link px-5"
                            >Account Settings</a
                        >
                    </div>
                    <div class="menu-item px-5">
                        <a
                            href="authentication/layouts/corporate/sign-in.html"
                            class="menu-link px-5"
                            >Sign Out</a
                        >
                    </div>
                </div>
            </div> -->
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
