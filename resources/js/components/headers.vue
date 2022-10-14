<template>
    <nav
        class="headers"
        id="header"
        data-test-id="header-container"
        data-desktop-id="header-inner"
        aria-label="Primary"
    >
        <a href="javascript:" class="app-logo" aria-label="Back to home"
            ><div class="h-100 d-flex align-items-center">
                <div class="logo d-flex align-items-center">
                    <img src="img/etask.png" height="23" alt="" class="me-2" />
                    E-Tasks
                </div>
            </div>
        </a>
        <div class="menu-left">
            <div class="menu-left-container position-relative">
                <div>
                    <button
                        class="nav-items nav-global"
                        data-test-id="workspace-switcher"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        title="Workspaces"
                        aria-label="Workspaces"
                        data-bs-custom-class="tooltip-inverse"
                        data-bs-placement="left"
                        data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-start"
                        data-kt-menu-offset="0px, 5px"
                    >
                        <span
                            class="nav-item-title me-2 text-light text-capitalize"
                            >Tambah data</span
                        >
                        <span
                            class="nch-icon zb2xH7sIF-ak3S ttnK8PUoemc3bn yL4b0Ecr10JiXu frgVIeU5oJb3WB"
                            ><span
                                role="img"
                                aria-label="DownIcon"
                                class="css-pxzk9z"
                                style="
                                    --icon-primary-color: var(
                                        --ds-text-accent-gray-bolder,
                                        #172b4d
                                    );
                                    --icon-secondary-color: inherit;
                                "
                                ><svg
                                    width="24"
                                    height="24"
                                    role="presentation"
                                    focusable="false"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M11.2929 16.7071L4.22185 9.63606C3.83132 9.24554 3.83132 8.61237 4.22185 8.22185C4.61237 7.83133 5.24554 7.83133 5.63606 8.22185L12 14.5858L18.364 8.22185C18.7545 7.83132 19.3877 7.83132 19.7782 8.22185C20.1687 8.61237 20.1687 9.24554 19.7782 9.63606L12.7071 16.7071C12.3166 17.0977 11.6834 17.0977 11.2929 16.7071Z"
                                        fill="currentColor"
                                    ></path></svg></span
                        ></span>
                    </button>
                    <div
                        class="menu menu-user menu-sub menu-sub-dropdown menu-column menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                        data-kt-menu="true"
                    >
                        <div class="menu-item px-3">
                            <a
                                href="javascript:"
                                class="menu-link px-3 fw-bold"
                                @click="showModalAddStaff"
                            >
                                <i class="fa-solid fa-plus me-3"></i>
                                ASN Terkait
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a
                                href="javascript:"
                                class="menu-link px-3 fw-bold"
                                data-bs-toggle="modal"
                                data-bs-target="#addSptModal"
                            >
                                <i class="fa-solid fa-plus me-3"></i>
                                Dasar SPT
                            </a>
                        </div>
                    </div>
                    <button
                        class="nav-items nav-global"
                        data-test-id="recently-viewed-boards-menu"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        title="Recent"
                        aria-label="Recent boards"
                    >
                        <span class="nav-item-title me-2 text-light"
                            >Recent</span
                        ></button
                    ><button
                        class="nav-items nav-global"
                        data-test-id="starred-boards-menu"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        title="Starred"
                        aria-label="Starred boards"
                    >
                        <a
                            class="nav-item-title me-2 text-light"
                            href="javascript:"
                            data-bs-toggle="modal"
                            data-bs-target="#dasarSpt"
                            >Dasar SPT</a
                        >
                    </button>
                    <button
                        class="nav-items nav-global profile-name"
                        data-test-id="recently-viewed-boards-menu"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        title="Recent"
                        aria-label="Recent boards"
                    >
                        <span class="nav-item-title me-2 text-light">{{
                            user.name
                        }}</span>
                    </button>
                </div>
                <div class="dyyadVYdVIR0rS">
                    <div
                        style="display: block; width: 100%; position: absolute"
                    ></div>
                </div>
            </div>
        </div>
        <div class="menu-right">
            <button
                class="menu-seru main-right"
                data-test-id="header-info-button"
                type="button"
                aria-label="Open information menu"
            >
                <span
                    class="nch-icon zb2xH7sIF-ak3S _7DPC8ZrrlGASp7 xSdEzXN0p5IsmX qlkxyoNXkcpPFk"
                    ><span
                        role="img"
                        aria-label="InformationIcon"
                        class="css-pxzk9z"
                        style="
                            --icon-primary-color: var(
                                --ds-text-accent-gray-bolder,
                                #172b4d
                            );
                            --icon-secondary-color: inherit;
                        "
                        ><svg
                            width="24"
                            height="24"
                            role="presentation"
                            focusable="false"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                fill="currentColor"
                            ></path>
                            <path
                                d="M11 11C11 10.4477 11.4477 10 12 10C12.5523 10 13 10.4477 13 11V16C13 16.5523 12.5523 17 12 17C11.4477 17 11 16.5523 11 16V11Z"
                                fill="currentColor"
                            ></path>
                            <path
                                d="M13 8C13 8.55228 12.5523 9 12 9C11.4477 9 11 8.55228 11 8C11 7.44772 11.4477 7 12 7C12.5523 7 13 7.44772 13 8Z"
                                fill="currentColor"
                            ></path></svg></span
                ></span>
            </button>
            <div class="FmsNZYly9TPRkA"></div>
            <button
                class="main-right menu-profile nav-items nav-global"
                type="button"
                data-kt-menu-trigger="hover"
                data-kt-menu-placement="bottom-start"
                data-kt-menu-offset="0px, 5px"
            >
                <div class="profile-container">
                    <img
                        :src="user.foto"
                        @error="onErrorImg"
                        class="p6oJr7SHjK+vLr aqePx81u4BGHTH Glb3QqRGpd64YB"
                        style="height: 25px; width: 25px; line-height: 32px"
                    />
                </div>
            </button>
            <div
                class="menu menu-user menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                data-kt-menu="true"
            >
                <div class="menu-item px-3">
                    <form method="POST" action="/logout">
                        <input type="hidden" name="_token" :value="csrf" />
                        <button
                            type="submit"
                            class="menu-link px-3 text-danger logout-btn"
                        >
                            <i
                                class="fa-solid fa-right-from-bracket me-2 text-danger fw-bold"
                            ></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            rule: null,
            user: [],
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };
    },
    props: {
        users: Array | Object,
    },
    watch: {
        users() {
            this.user = this.users;
        },
    },
    methods: {
        showModalAddStaff() {
            this.$root.$emit("addStaff");
        },
        onErrorImg(e) {
            e.target.src = "img/user.png";
        },
    },
};
</script>

<style lang="scss" scoped>
nav {
    background-color: #59599770;
    transition: background-color 300ms;
    border-bottom: 1px solid var(--dynamic-text-color-transparent);
    box-sizing: border-box;
    display: flex;
    -webkit-backdrop-filter: blur(6px);
    backdrop-filter: blur(6px);
    max-height: 44px;
    padding: 6px 20px;

    .app-logo {
        display: block;
        position: relative;
        flex-shrink: 0;
        height: 30px;
        margin-top: 1px;
        padding: 0 6px;
        height: 32px;
        border-radius: 3px;

        .logo {
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            font-family: "Courgette", cursive;
        }
    }

    .menu-left {
        display: flex;
        flex-basis: 100%;

        .menu-left-container {
            align-items: stretch;
            display: flex;
            flex-grow: 1;
            flex-shrink: 0;
            flex-basis: 0;
            height: 100%;
            position: relative;
        }

        .menu-left-container > * {
            display: flex;
            flex-shrink: 0;
            margin: 0 4px;

            button.profile-name {
                position: absolute;
                right: 0;
                cursor: none;
                background-color: rgba(255, 255, 255, 0.3019607843);
                box-shadow: none;
                color: #fff;
                border: none;
                color: var(--ds-text, inherit);
            }

            .nav-global {
                color: #172b4d;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
                    "Roboto", "Noto Sans", "Ubuntu", "Droid Sans",
                    "Helvetica Neue", sans-serif;
                font-size: 14px;
                line-height: 20px;
                font-weight: 400;
                box-sizing: border-box;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 3px;
                cursor: pointer;
                padding: 6px 12px;
                text-decoration: none;
                white-space: normal;
                background-color: var(
                    --ds-background-neutral,
                    rgba(9, 30, 66, 0.04)
                );
                box-shadow: none;
                border: none;
                color: var(--ds-text, inherit);
                transition-property: background-color, border-color, box-shadow;
                transition-duration: 85ms;
                transition-timing-function: ease;
            }

            .nav-global:hover {
                background-color: #ffffff4d;
                box-shadow: none;
                color: #fff;
                border: none;
                color: var(--ds-text, inherit);
            }

            .nav-items {
                background-color: transparent;
                height: 32px;
                line-height: 32px;
                margin-right: 4px;
                padding-right: 10px;
                font-weight: 600;
                white-space: nowrap;
                .nch-icon {
                    margin-left: 4px;
                    min-width: 16px;
                    display: inline-block;
                    line-height: 1;
                    color: #fff;
                }
            }
        }
    }

    .menu-right {
        display: flex;
        justify-content: flex-end;
        flex-grow: 1;

        .menu-seru {
            background-color: rgba(255, 255, 255, 0);
            color: #fff;
            box-shadow: none;
            border: none;
        }

        .main-right {
            border-radius: 3px;
            text-decoration: none;
            align-items: center;
            display: flex;
            font-weight: bold;
            height: 32px;
            line-height: 32px;
            margin: 0 4px 0 0;
            padding: 6px;
            transition: 0.5s;
            white-space: nowrap;
        }
        .menu-seru:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        button.menu-profile {
            background-color: #0ad6e2;
            border: none;
            border-radius: 50%;
            width: 30px;
            overflow: hidden;
            padding: 0;
            height: 30px;
            justify-content: center;
            display: flex;

            .profile-container {
                position: relative;
                line-height: 10px;
                overflow: hidden;
                white-space: nowrap;
            }
        }

        button.menu-profile:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
    }
}
</style>
