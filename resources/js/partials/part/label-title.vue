<template>
    <div class="label-title d-flex align-items-center">
        <i :class="icon"></i>
        <label class="label-text ps-3">{{ title }}</label>
    </div>
</template>

<script>
export default {
    props:{
        title: String,
        icon: String
    }
};
</script>

<style lang="scss" scoped>
.label-title i {
    font-size: 16px;
    color: #67696f;
}
.label-text{
    font-weight: 600;
}
</style>
