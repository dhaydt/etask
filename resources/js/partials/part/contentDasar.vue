<template>
    <tr class="contentDasar">
        <td>
            <div class="symbol symbol-45px me-2">
                <span class="symbol-label">
                    <img
                        src="img/order.webp"
                        class="h-75 align-self-center"
                        alt=""
                    />
                    <!-- <i class="fa-solid fa-book" style="font-size:2rem;"></i> -->
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
                class="justify-content-end form-check form-switch form-check-custom form-check-solid"
            >
                <label
                    v-if="active == false"
                    class="form-check-label me-2"
                    for="flexSwitchDefault"
                >
                    <span class="badge badge-light-danger">Non Active</span>
                </label>
                <label v-else class="form-check-label me-2" for="flexSwitchDefault">
                    <span class="badge badge-light-success">Active</span>
                </label>
                <input
                    class="form-check-input h-20px w-30px cursor-pointer"
                    v-model="active"
                    type="checkbox"
                    id="flexSwitchDefault"
                    @click="updateDasar(dasar.id)"
                />
            </div>
        </td>
        <!-- <td class="text-center">
            <div class="d-flex justify-content-center">
                <button @click="modalEdit(dasar)" class="btn btn-sm btn-light-success"
                    ><i class="fa-solid fa-edit"></i
                ></button>
            </div>
        </td> -->
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
        modalEdit(data){
            $('#sptEdit').modal('show');
        },
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
                    that.updateDasarStatus(data.data);
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
        },
        updateDasarStatus(data){
            this.$parent.updateDasarStatus(data);
        }
    }
};
</script>

<style lang="scss" scoped></style>
