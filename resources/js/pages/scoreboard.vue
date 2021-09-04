<template>
  <v-card>
    <v-card-title>
      Scoreboard
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="desserts"
      :search="search"
    ></v-data-table>
  </v-card>
</template>

<script>
  export default {
    data () {
      return {
        search: '',
        headers: [
          {
            text: 'ชื่อ',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'งาน', value: 'work' },
          { text: 'คะแนนรวม', value: 'score' },
        ],
        desserts: [
          {
            ชื่อ: 'แดง แจ๊ด',
            งาน: 159,
            คะแนนรวม: 6.0,
          },
          {
            ชื่อ: 'เหลือง อ๋อย',
            งาน: 159,
            คะแนนรวม: 6.0,
          },
          {
            ชื่อ: 'เขียว อี้',
            งาน: 159,
            คะแนนรวม: 6.0,
          },
          {
            ชื่อ: 'ม่วง มัว',
            งาน: 159,
            คะแนนรวม: 6.0,
          },
          {
            ชื่อ: 'ดำ มืด',
            งาน: 159,
            คะแนนรวม: 6.0,
          },
          {
            ชื่อ: 'ขาว มาก',
            งาน: 159,
            คะแนนรวม: 6.0,
          },
          {
            ชื่อ: 'ฟ้า คราม',
            งาน: 159,
            คะแนนรวม: 6.0,
          },
        ],
      }
    },
  }
</script>

<style>
</style>