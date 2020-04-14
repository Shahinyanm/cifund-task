<template>
    <section class="section">
        <div class="container">
            <h1 class="title">
                PHP Developer Test
            </h1>
            <p class="subtitle">
                Laravel & Vue.js!
            </p>
            <form>
                <b-field grouped>
                    <b-field label="Name">
                        <b-input v-model="searchData.name"></b-input>
                    </b-field>
                    <b-field label="Bedrooms">
                        <b-numberinput v-model="searchData.bedrooms" min="0" :editable="true"
                                       controls-position="compact"></b-numberinput>
                    </b-field>
                    <b-field label="Bathrooms">
                        <b-numberinput v-model="searchData.bathrooms" min="0" :editable="true"
                                       controls-position="compact"></b-numberinput>
                    </b-field>
                    <b-field label="Storeys">
                        <b-numberinput v-model="searchData.storeys" min="0" :editable="true"
                                       controls-position="compact"></b-numberinput>
                    </b-field>
                    <b-field label="Garages">
                        <b-numberinput v-model="searchData.garages" min="0" :editable="true"
                                       controls-position="compact"></b-numberinput>
                    </b-field>
                    <b-field :label="'Price ($' + searchData.price[0] + '-$' + searchData.price[1] + ')'" expanded>
                        <b-slider v-model="searchData.price" :min="1" :max="20000" :step="10" ticks rounded>
                        </b-slider>
                    </b-field>
                </b-field>
                <b-field>
                    <b-button type="is-primary" @click="search">Search</b-button>
                </b-field>
            </form>
            <h5 class="title is-5 mt-30">Results</h5>
            <b-table :data="results" :columns="columns" :loading="loading">
                <template slot="empty">
                    <section class="section">
                        <div class="content has-text-grey has-text-centered">
                            <p>
                                <b-icon
                                        icon="emoticon-sad"
                                        size="is-large">
                                </b-icon>
                            </p>
                            <p>{{emptySearchText}}</p>
                        </div>
                    </section>
                </template>
            </b-table>
        </div>
    </section>
</template>

<script>
  export default {
    data () {
      return {
        loading: false,
        searchData: {
          name: '',
          bedrooms: 0,
          bathrooms: 0,
          storeys: 0,
          garages: 0,
          price: [],
          emptyText: 'Nothing here.',
        },
        data: [],
        columns: [
          {
            field: 'id',
            label: 'ID',
            width: '40',
            numeric: true,
          },
          {
            field: 'name',
            label: 'Name',
          },
          {
            field: 'price',
            label: 'Price',
          },
          {
            field: 'bedrooms',
            label: 'Bedrooms',
            centered: true,
          },
          {
            field: 'bathrooms',
            label: 'Bathrooms',
          },
          {
            field: 'storeys',
            label: 'Storeys',
          },
          {
            field: 'garages',
            label: 'Garages',
          },
          {
            field: 'created_at',
            label: 'Date',
          },
        ],
      }
    },
    watch: {
      searchData: {
        handler (val) {
          let data = JSON.parse(JSON.stringify(val))
          data.price = { start: val.price[0], end: val.price[1] }
          this.search(data)
        },
        deep: true,
      },
    },
    computed: {
      results () {
        return this.data
      },
      emptySearchText () {
        return this.emptyText
      },
    },

    methods: {
      search (searchData) {
        this.loading = true

        searchData = Object.keys(searchData).filter(key => searchData[key] !== 0).reduce((obj, key) => {
          obj[key] = searchData[key]
          return obj
        }, {})

        axios.get('/api/hotels/search', { params: searchData }).then(r => {
          this.data = []
          this.data.push(...r.data.data)
          this.loading = false
        }).catch((error) => {
          console.log(error)
          this.emptyText =error.response.data.message;
        })
      },
    },
  }
</script>