<template>
    <tr v-if="show" class="pegawai">
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
                class="form-check form-switch form-check-custom form-check-solid position-relative"
            >
                <div class="check">
                    <i class="fa-solid fa-save"></i>
                    <input
                        class="form-check-input"
                        v-model="active"
                        type="checkbox"
                        data-bs-toggle="tooltip"
                        title="Simpan"
                        id="flexSwitchDefault"
                        @click="simpan(dasar)"
                    />
                </div>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        dasar: Object,
        selected: Object | Array,
    },
    data() {
        return {
            show : true,
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
        this.checkStatus(this.dasar.nip);
    },
    watch:{
        dasar(){
            this.checkStatus(this.dasar.nip);
            this.updateActive();
        },
        selected(){
            this.checkStatus(this.dasar.nip);
            this.updateActive();
        }
    },
    methods:{
        checkStatus(nip){
            var checkId = obj => obj.nip == nip.toString();
            // console.log('selectedPeg', this.selected.some(checkId), nip, this.selected)
            if(this.selected.some(checkId) == true){
                this.show = false;
            }else{
                this.show = true;
            }
        },
        simpan(user){
            var active = this.active;
            var that = this;
            this.show = false;
            axios.post('addStaff',{
                user: user,
                status: active
            }, this.config).then(function(resp){
                var data = resp.data;
                if(data.code == 200){
                    that.$parent.splitAxios(data.data.original);
                    Vue.$toast.success(data.message);
                    // console.log('respon',data);
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
    #flexSwitchDefault{
        opacity: 0;
        z-index: 1;
        cursor: pointer;
    }

    .check:hover i{
        transform: scale(1.2);
        color: rgb(0, 195, 0);
    }

    .form-check i{
        position: absolute;
        left: 0;
        font-size: 30px;
        transition: 0.5s;
    }
</style>
