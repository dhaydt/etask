<template>
    <tr>
        <td>
            <div class="symbol symbol-45px me-2">
                <span class="symbol-label">
                    <img
                        src="assets/media/svg/brand-logos/plurk.svg"
                        class="h-50 align-self-center"
                        alt=""
                    />
                </span>
            </div>
        </td>
        <td>
            <a
                href="javascript:"
                class="text-dark fw-bold text-hover-primary mb-1 fs-6"
                >{{ dasar.dasar }}</a
            >
        </td>
        <td class="text-center text-muted fw-bold">{{ dasar.keterangan }}</td>
        <td class="text-center">
            <div
                class="form-check form-switch form-check-custom form-check-solid"
            >
                <input
                    class="form-check-input h-20px w-30px"
                    v-model="active"
                    type="checkbox"
                    id="flexSwitchDefault"
                    @click="updateDasar(dasar.id)"
                />
                <label
                    v-if="active == false"
                    class="form-check-label"
                    for="flexSwitchDefault"
                >
                    <span class="badge badge-light-danger">Non Active</span>
                </label>
                <label v-else class="form-check-label" for="flexSwitchDefault">
                    <span class="badge badge-light-success">Active</span>
                </label>
            </div>
        </td>
        <td class="text-center">
            <div class="d-flex justify-content-center">
                <a href="javascript:" class="btn btn-sm btn-light-success"
                    ><i class="fa-solid fa-edit"></i
                ></a>
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
        updateDasar(id){
            var active = this.active;
            var that = this;
            console.log('status', active, id)
            axios.post('updateDasarStatus',{
                id: id,
                status: active
            }, this.config).then(function(resp){
                var data = resp.data;
                if(data.code == 200){
                    Vue.$toast.success(data.message);
                }
                console.log('respon',data);
            }).catch(function(err){
                window.alert(err);
            })
        },
        updateActive(){
            if(this.dasar.status == 1){
                this.active = true
            }else{
                this.active = false
            }
        }
    }
};
</script>

<style lang="scss" scoped></style>
