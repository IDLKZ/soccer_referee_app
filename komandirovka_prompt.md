Создадим страничку Коммандировка для роли refereeing_department_logistician (Логист департамента футбольного судейства)
В коммандировках (logistician/business_trip.blade.php) будет карточка с матчами и фильтрами по статусу все матчи с operation.value in [select_responsible_logisticians, select_transport_departure, business_trip_plan_preparation, referee_team_confirmation, primary_business_trip_confirmation, final_business_trip_confirmation,business_trip_registration, business_trip_plan_reprocessing]
При нажатии на матч (logistician/business_trip_detail.blade.php),Будет следующие trips со operation_id
2.1 Выбор транспорта и указание точки отправления (operation.value == select_transport_departure)
2.2 Оформление плана командировки (operation.value == business_trip_plan_preparation)
2.3 Подтверждение судейной бригады (operation.value == referee_team_confirmation)
2.4 Первичное подтверждение командировки (operation.value == refereeing_department_employee)
2.5 Финальное подтверждение командировки (operation.value == final_business_trip_confirmation)
2.6 Оформление командировки (operation.value == business_trip_registration)
2.5 Переоформление плана командировки (operation.value == business_trip_plan_reprocessing)
В каждой вкладке trips c trip_hotels (модальное окно) trip_migrations (модальное окно) и trip_documents (модальное окно)
Каждый trips можно редактировать и добавлять trip_hotels (модальное окно) trip_migrations (модальное окно) и trip_documents (модальное окно) когда trips.operation_id in (value == business_trip_plan_preparation or business_trip_registration or business_trip_plan_reprocessing)
Создай красивый и функциональный дизайн с возможностью livewire и поддержкой light и dark theme
