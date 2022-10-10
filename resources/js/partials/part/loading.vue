<template>
    <div class="loading toast show bg-light-warning d-block">
        <div class="toast-body d-flex align-items-center">
            <div class="spinner-grow text-warning" role="status">
            </div>
            <label class="ms-2 fw-bold text-warning">Sedang mengambil data ASN Terkait</label>
        </div>
    </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.loading {
    position: fixed;
    bottom: 20px;
    transition: .5s;
    right: 20px;
    // background-color: red;
    .toast-body{
        label{
            font-size: 14px;
        }
    }
}
</style>
