<script setup>
    import { ref, onMounted } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import AccountBase from './AccountBase.vue'
    import axios from 'axios'
    import { useQuasar } from 'quasar'

    const router = useRouter()
    const $q = useQuasar()

    const form = ref({
        description: ''
    })
    
    const send = function() {
        axios.post(`company/new`, form.value)
            .then((response) => {
                $q.notify({ message: 'Организация создана', color: 'positive' })
                router.push(`/account/companies`)
            })
            .catch((error) => {
                console.log(error);
            })
    }

    const fields = [
        { name: 'name', label: 'Наименование организации', rules: [ val => val && val.length > 0 || 'Заполните это поле'] },
        { name: 'brand', label: 'Бренд' },
        { name: 'address', label: 'Адрес организации' },
        { name: 'phone', label: 'Рабочий телефон', type: 'tel' },
        { name: 'site', label: 'Сайт организации' },
        { name: 'email', label: 'E-mail организации', type: 'email' },
        { name: 'inn', label: 'ИНН' },
        { name: 'ogrn', label: 'ОГРН' }
    ]
</script>

<template>
    <account-base>
        <q-form @submit="send" class="box">
            <h2 class="text-h6">Введите общие данные организации:</h2>

            <div class="row q-col-gutter-lg">
                <template v-for="i in fields">
                    <div class="col-6">
                        <q-input v-model="form[i.name]" outlined :name="i.name" :type="i.type || 'text'" :label="i.label"
                            :rules="i.rules || null"
                        />
                    </div>
                </template>

                <div class="col-12">
                    <p>Описание организации</p>
                    <q-editor v-model="form.description" min-height="5rem" />
                </div>

                <div class="col-12">
                    <q-btn label="Отправить" type="submit" color="primary"/>
                </div>
            </div>
        </q-form>
    </account-base>
</template>