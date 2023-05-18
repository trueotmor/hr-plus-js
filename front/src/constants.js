const tableColumns = [
    { name: 'name', label: 'Наименование организации', field: 'name', align: 'left' },
    { name: 'inn', label: 'ИНН', field: 'inn', align: 'left', hideFrom: 400 },
    { name: 'vacancy', label: 'Вакансии', field: 'carbs', hideFrom: 650 },
    { name: 'users', label: 'Пользователи', field: 'users', hideFrom: 800 },
    { name: 'feedback', label: 'Отклики в работе', field: 'feedback', hideFrom: 900 },
    { name: 'action', label: 'Действие', field: 'action' },
    { name: 'user', label: 'Создатель аккаунта', field: 'user', align: 'left', hideFrom: 1000 },
    { name: 'createdon', label: 'Дата создания', field: 'createdon', align: 'left', hideFrom: 1200 },
]

const tableColumnsNames = ['name', 'inn', 'vacancy', 'users', 'feedback', 'action', 'user', 'createdon']

export { tableColumns, tableColumnsNames }