<template>
    <div>
        <span class="clap-btn" @click="likeReceta" :class="{'clap-active' : isActive}"></span>
        <p>{{cantidadLikes}} Les gustó esta receta</p>
    </div>
</template>

<script>
    export default {
    props:['recetaId','like', 'likes'],
    //Hacer el componente auto
    data: function(){
        return {
            isActive: this.like,
            totalLikes: this.likes
        }
    },
    // mounted(){
    //     console.log(this.like);
    // },
    methods:{
        likeReceta(){
            console.log('me gusta')
                axios.post('/recetas/' + this.recetaId)
                .then(respuesta => {
                    if(respuesta.data.attached.length > 0 ) {
                            this.$data.totalLikes++;
                        } else {
                            this.$data.totalLikes--;
                        }

                        this.isActive = !this.isActive
                })
                .catch(error => {
                    if (error.response.status === 401) {
                        window.location = '/register';
                    }
                });
            }
        },

        computed: {
            cantidadLikes: function() {
                return this.totalLikes
            }
        }
    }
</script>