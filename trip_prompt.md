Создадим страничку Мои коммандировки на матч для роли soccer_referee (Футбольный судья)
1. Создадим страничку Мои коммандировки (referee/my_business_trips) и добавим также в sidebar для soccer_referee
2. В Моих коммандировках будет три tabs ->
   2.1 - Выбор транспорта и указание точки отправления (match.operation_id == operation.value ==  select_transport_departure),
   2.2 - Ожидают моего подтверждения где уже trips.operation_id == operation.value == primary_business_trip_confirmation и trips.judge_id == Auth()->id
   2.3 - Мои готовые коммандировки там где уже trips.operation_id == operation.value == business_trip_registration и trips.judge_id == Auth()->id
3. При нажатии на trips выходит полная информация с учетом trip_documents, trip_hotels, trip_migrations
4. При выборе Выбор транспорта и указание точки отправления (если у данного матча нет trips.judge_id == user_id) то soccer_referee - выбирает departure_city_id, transport_type_id, остальные поля подтягиваются из match, name -> заполняется как owner_club - guest_club, date, а operation_id выставляется в operation.value == business_trip_plan_preparation
5. При Ожидают моего подтверждения мы можем выбрать Принять или отказать (judge_status,  0 - waiting, -1 - reject, 1 - ok) при этом judge может отписать причину в judge_comment, при отказе trips переходит operation_id выставляется в operation.value == business_trip_plan_reprocessing, а при принятии в operation.value == primary_business_trip_confirmation при этом поля как first_status и final_status переходят в 0
6. Создай красивый и респонсивный дизайн на livewire с поддержкой light dark themes, при нажатии на trips грузим через жадную загрузку все поля как trip_hotels, trip_migrations, trip_documents, а на матч информация о матче
7. Добавь вкладку Мои Коммандировки в resources/views/layouts/partials/sidebars/referee.blade.php
