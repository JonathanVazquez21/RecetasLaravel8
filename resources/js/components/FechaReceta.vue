<template>
   <span>{{ formatearFecha}}</span>
</template>
<script>
export default {
   //Una ves que esta listo el component lo puedes ver mounted()
   props:['fecha'],
      computed:{
            formatearFecha(){
               
               return moment(this.fecha).locale('es').format('DD [de] MMMM [del] YYYY');
            }
      }
  
}
</script>