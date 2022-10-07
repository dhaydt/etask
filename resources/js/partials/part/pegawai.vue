<template>
    <tr>
        <td>
            <div class="symbol symbol-45px me-2">
                <span class="symbol-label">
                    <img
                        :src="dasar.foto"
                        class="h-100 align-self-center"
                        alt=""
                    />
                </span>
            </div>
        </td>
        <td>
            <a
                href="javascript:"
                class="text-dark fw-bold text-hover-primary mb-1 fs-6"
                >{{ dasar.nama_pegawai }}</a
            >
        </td>
        <td class="text-center text-muted fw-bold">{{ dasar.nama_jabatan }}</td>
        <td class="text-center">
            <div
                class="form-check form-switch form-check-custom form-check-solid"
            >
                <input
                    class="form-check-input"
                    v-model="active"
                    type="checkbox"
                    id="flexSwitchDefault"
                    @click="simpan(dasar)"
                />
                <label
                    v-if="active == false"
                    class="form-check-label"
                    for="flexSwitchDefault"
                >
                    <span class="badge badge-light-danger">Tidak Tersimpan</span>
                </label>
                <label v-else class="form-check-label" for="flexSwitchDefault">
                    <span class="badge badge-light-success">Tersimpan</span>
                </label>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        dasar: Object,
    },
    data() {
        return {
            active: false,
            config: {
                headers: {
                    header: document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            },
        };
    },
    mounted(){
        this.updateActive();
    },
    watch:{
        dasar(){
            this.updateActive();
        }
    },
    methods:{
        simpan(user){
            var active = this.active;
            var that = this;
            console.log('status', active)
            axios.post('addStaff',{
                user: user,
                status: active
            }, this.config).then(function(resp){
                var data = resp.data;
                if(data.code == 200){
                    that.$parent.splitAxios(data.data.original);
                    Vue.$toast.success(data.message);
                    console.log('respon',data);
                }
            }).catch(function(err){
                console.log('err',err);
                // window.alert(err);
            })
        },
        updateActive(){
            this.active = this.dasar.status;
        },
        updateDasarStatus(data){
            this.$parent.updateDasarStatus(data);
        }
    }
};
</script>

<style lang="scss" scoped>
    .symbol{
        overflow: hidden;
        cursor: pointer;
    }
</style>
