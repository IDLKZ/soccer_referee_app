<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Operation;
use App\Models\CategoryOperation;
use App\Constants\CategoryOperationConstants;
use App\Constants\RoleConstants;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $refAssignmentCat = CategoryOperation::where('value', CategoryOperationConstants::REFEREE_ASSIGNMENT)->first();
        $businessTripCat = CategoryOperation::where('value', CategoryOperationConstants::BUSINESS_TRIP)->first();
        $matchProtocolCat = CategoryOperation::where('value', CategoryOperationConstants::MATCH_PROTOCOL)->first();
        $avrCat = CategoryOperation::where('value', CategoryOperationConstants::AVR)->first();
        $paymentCat = CategoryOperation::where('value', CategoryOperationConstants::PAYMENT)->first();
        $finalResultCat = CategoryOperation::where('value', CategoryOperationConstants::FINAL_RESULT)->first();

        // First pass: Create all operations without relationships
        $operations = [];

        // 1. Referee Assignment
        $operations['ref_1_1'] = Operation::updateOrCreate(
            ['value' => 'match_created_waiting_referees'],
            [
                'category_id' => $refAssignmentCat->id,
                'title_ru' => 'Матч создан, ожидание добавления судей',
                'title_kk' => 'Матч жасалды, төрешілерді қосуды күту',
                'title_en' => 'Match created, waiting for referees',
                'description_ru' => 'Ожидание назначения судейской бригады на матч',
                'description_kk' => 'Матчқа төреші бригадасын тағайындауды күту',
                'description_en' => 'Waiting for referee team assignment to match',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE, RoleConstants::SOCCER_REFEREE],
                'is_first' => true,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['ref_1_2'] = Operation::updateOrCreate(
            ['value' => 'referee_team_approval'],
            [
                'category_id' => $refAssignmentCat->id,
                'title_ru' => 'Утверждение судейской бригады',
                'title_kk' => 'Төреші бригадасын бекіту',
                'title_en' => 'Referee team approval',
                'description_ru' => 'Директор судейской бригады утверждает состав',
                'description_kk' => 'Төреші бригадасының директоры құрамды бекітеді',
                'description_en' => 'Referee committee director approves the team',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_HEAD],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['ref_1_3'] = Operation::updateOrCreate(
            ['value' => 'referee_reassignment'],
            [
                'category_id' => $refAssignmentCat->id,
                'title_ru' => 'Переназначение судей',
                'title_kk' => 'Төрешілерді қайта тағайындау',
                'title_en' => 'Referee reassignment',
                'description_ru' => 'Изменение состава судейской бригады',
                'description_kk' => 'Төреші бригадасының құрамын өзгерту',
                'description_en' => 'Changing referee team composition',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE, RoleConstants::SOCCER_REFEREE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        // 2. Business Trip
        $operations['trip_2_1'] = Operation::updateOrCreate(
            ['value' => 'select_responsible_logisticians'],
            [
                'category_id' => $businessTripCat->id,
                'title_ru' => 'Выбор ответственных логистов',
                'title_kk' => 'Жауапты логистерді таңдау',
                'title_en' => 'Select responsible logisticians',
                'description_ru' => 'Назначение логистов для организации командировки',
                'description_kk' => 'Іссапарды ұйымдастыру үшін логистерді тағайындау',
                'description_en' => 'Assigning logisticians for business trip organization',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE],
                'is_first' => true,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['trip_2_2'] = Operation::updateOrCreate(
            ['value' => 'select_transport_departure'],
            [
                'category_id' => $businessTripCat->id,
                'title_ru' => 'Выбор транспорта и указание точки отправления',
                'title_kk' => 'Көлікті таңдау және жөнелту нүктесін көрсету',
                'title_en' => 'Select transport and departure point',
                'description_ru' => 'Судья выбирает транспорт и точку отправления',
                'description_kk' => 'Төреші көлікті және жөнелту нүктесін таңдайды',
                'description_en' => 'Referee selects transport and departure point',
                'responsible_roles' => [RoleConstants::SOCCER_REFEREE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['trip_2_3'] = Operation::updateOrCreate(
            ['value' => 'business_trip_plan_preparation'],
            [
                'category_id' => $businessTripCat->id,
                'title_ru' => 'Оформление плана командировки',
                'title_kk' => 'Іссапар жоспарын ресімдеу',
                'title_en' => 'Business trip plan preparation',
                'description_ru' => 'Логист оформляет план командировки',
                'description_kk' => 'Логист іссапар жоспарын ресімдейді',
                'description_en' => 'Logistician prepares business trip plan',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['trip_2_4'] = Operation::updateOrCreate(
            ['value' => 'referee_team_confirmation'],
            [
                'category_id' => $businessTripCat->id,
                'title_ru' => 'Подтверждение судейной бригады',
                'title_kk' => 'Төреші бригадасын растау',
                'title_en' => 'Referee team confirmation',
                'description_ru' => 'Судья подтверждает план командировки',
                'description_kk' => 'Төреші іссапар жоспарын растайды',
                'description_en' => 'Referee confirms business trip plan',
                'responsible_roles' => [RoleConstants::SOCCER_REFEREE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['trip_2_5'] = Operation::updateOrCreate(
            ['value' => 'primary_business_trip_confirmation'],
            [
                'category_id' => $businessTripCat->id,
                'title_ru' => 'Первичное подтверждение командировки',
                'title_kk' => 'Іссапардың алғашқы растауы',
                'title_en' => 'Primary business trip confirmation',
                'description_ru' => 'Сотрудник департамента судейства подтверждает командировку',
                'description_kk' => 'Төрешілік департаментінің қызметкері іссапарды растайды',
                'description_en' => 'Refereeing department employee confirms business trip',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['trip_2_6'] = Operation::updateOrCreate(
            ['value' => 'final_business_trip_confirmation'],
            [
                'category_id' => $businessTripCat->id,
                'title_ru' => 'Финальное подтверждение командировки',
                'title_kk' => 'Іссапардың соңғы растауы',
                'title_en' => 'Final business trip confirmation',
                'description_ru' => 'Директор департамента судейства финально подтверждает командировку',
                'description_kk' => 'Төрешілік департаментінің директоры іссапарды соңғы растайды',
                'description_en' => 'Refereeing department director gives final confirmation',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_HEAD],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['trip_2_7'] = Operation::updateOrCreate(
            ['value' => 'business_trip_registration'],
            [
                'category_id' => $businessTripCat->id,
                'title_ru' => 'Оформление командировки',
                'title_kk' => 'Іссапарды ресімдеу',
                'title_en' => 'Business trip registration',
                'description_ru' => 'Логист оформляет командировку',
                'description_kk' => 'Логист іссапарды ресімдейді',
                'description_en' => 'Logistician registers business trip',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['trip_2_8'] = Operation::updateOrCreate(
            ['value' => 'business_trip_plan_reprocessing'],
            [
                'category_id' => $businessTripCat->id,
                'title_ru' => 'Переоформление плана командировки',
                'title_kk' => 'Іссапар жоспарын қайта ресімдеу',
                'title_en' => 'Business trip plan reprocessing',
                'description_ru' => 'Логист переоформляет план командировки',
                'description_kk' => 'Логист іссапар жоспарын қайта ресімдейді',
                'description_en' => 'Logistician reprocesses business trip plan',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        // 3. Match Protocol
        $operations['prot_3_1'] = Operation::updateOrCreate(
            ['value' => 'waiting_for_protocol'],
            [
                'category_id' => $matchProtocolCat->id,
                'title_ru' => 'Ожидание протокола',
                'title_kk' => 'Хаттаманы күту',
                'title_en' => 'Waiting for protocol',
                'description_ru' => 'Ожидание заполнения протокола судьей',
                'description_kk' => 'Төрешінің хаттаманы толтыруын күту',
                'description_en' => 'Waiting for referee to fill protocol',
                'responsible_roles' => [RoleConstants::SOCCER_REFEREE],
                'is_first' => true,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['prot_3_2'] = Operation::updateOrCreate(
            ['value' => 'primary_protocol_approval'],
            [
                'category_id' => $matchProtocolCat->id,
                'title_ru' => 'Первичное утверждение протокола',
                'title_kk' => 'Хаттаманың алғашқы бекітілуі',
                'title_en' => 'Primary protocol approval',
                'description_ru' => 'Сотрудник департамента судейства утверждает протокол',
                'description_kk' => 'Төрешілік департаментінің қызметкері хаттаманы бекітеді',
                'description_en' => 'Refereeing department employee approves protocol',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['prot_3_3'] = Operation::updateOrCreate(
            ['value' => 'control_protocol_approval'],
            [
                'category_id' => $matchProtocolCat->id,
                'title_ru' => 'Контрольное утверждение протокола',
                'title_kk' => 'Хаттаманың бақылау бекітілуі',
                'title_en' => 'Control protocol approval',
                'description_ru' => 'Директор департамента судейства контрольно утверждает протокол',
                'description_kk' => 'Төрешілік департаментінің директоры хаттаманы бақылау бекітеді',
                'description_en' => 'Refereeing department director gives control approval',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_HEAD],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['prot_3_4'] = Operation::updateOrCreate(
            ['value' => 'protocol_reprocessing'],
            [
                'category_id' => $matchProtocolCat->id,
                'title_ru' => 'Переоформление протокола',
                'title_kk' => 'Хаттаманы қайта ресімдеу',
                'title_en' => 'Protocol reprocessing',
                'description_ru' => 'Судья переоформляет протокол',
                'description_kk' => 'Төреші хаттаманы қайта ресімдейді',
                'description_en' => 'Referee reprocesses protocol',
                'responsible_roles' => [RoleConstants::SOCCER_REFEREE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        // 4. AVR
        $operations['avr_4_1'] = Operation::updateOrCreate(
            ['value' => 'avr_created_waiting_processing'],
            [
                'category_id' => $avrCat->id,
                'title_ru' => 'АВР Создан, ожидает оформления',
                'title_kk' => 'АЕҚ жасалды, ресімделуін күтеді',
                'title_en' => 'AVR created, waiting processing',
                'description_ru' => 'АВР создан и ожидает оформления сотрудником департамента',
                'description_kk' => 'АЕҚ жасалды және департамент қызметкерінің ресімдеуін күтеді',
                'description_en' => 'AVR created and waiting for department employee processing',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE],
                'is_first' => true,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['avr_4_2'] = Operation::updateOrCreate(
            ['value' => 'avr_processing'],
            [
                'category_id' => $avrCat->id,
                'title_ru' => 'Оформление АВР',
                'title_kk' => 'АЕҚ ресімдеу',
                'title_en' => 'AVR processing',
                'description_ru' => 'Сотрудник департамента судейства оформляет АВР',
                'description_kk' => 'Төрешілік департаментінің қызметкері АЕҚ ресімдейді',
                'description_en' => 'Refereeing department employee processes AVR',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['avr_4_3'] = Operation::updateOrCreate(
            ['value' => 'referee_confirmation'],
            [
                'category_id' => $avrCat->id,
                'title_ru' => 'Подтверждение судьи',
                'title_kk' => 'Төрешінің растауы',
                'title_en' => 'Referee confirmation',
                'description_ru' => 'Судья подтверждает АВР',
                'description_kk' => 'Төреші АЕҚ растайды',
                'description_en' => 'Referee confirms AVR',
                'responsible_roles' => [RoleConstants::SOCCER_REFEREE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['avr_4_4'] = Operation::updateOrCreate(
            ['value' => 'avr_approval_by_committee'],
            [
                'category_id' => $avrCat->id,
                'title_ru' => 'Утверждение АВР судейским комитетом',
                'title_kk' => 'Төреші комитетінің АЕҚ бекітуі',
                'title_en' => 'AVR approval by committee',
                'description_ru' => 'Директор департамента судейства утверждает АВР',
                'description_kk' => 'Төрешілік департаментінің директоры АЕҚ бекітеді',
                'description_en' => 'Refereeing department director approves AVR',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_HEAD],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['avr_4_5'] = Operation::updateOrCreate(
            ['value' => 'primary_financial_check'],
            [
                'category_id' => $avrCat->id,
                'title_ru' => 'Первичная финансовая проверка',
                'title_kk' => 'Алғашқы қаржылық тексеру',
                'title_en' => 'Primary financial check',
                'description_ru' => 'Экономист финансового департамента проверяет АВР',
                'description_kk' => 'Қаржы департаментінің экономисі АЕҚ тексереді',
                'description_en' => 'Finance department specialist checks AVR',
                'responsible_roles' => [RoleConstants::FINANCE_DEPARTMENT_SPECIALIST],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['avr_4_6'] = Operation::updateOrCreate(
            ['value' => 'control_financial_check'],
            [
                'category_id' => $avrCat->id,
                'title_ru' => 'Контрольная финансовая проверка',
                'title_kk' => 'Бақылау қаржылық тексеру',
                'title_en' => 'Control financial check',
                'description_ru' => 'Директор финансового департамента контрольно проверяет АВР',
                'description_kk' => 'Қаржы департаментінің директоры АЕҚ бақылау тексереді',
                'description_en' => 'Finance department director gives control check',
                'responsible_roles' => [RoleConstants::FINANCE_DEPARTMENT_HEAD],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => true,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['avr_4_7'] = Operation::updateOrCreate(
            ['value' => 'avr_reprocessing'],
            [
                'category_id' => $avrCat->id,
                'title_ru' => 'Переоформление АВР',
                'title_kk' => 'АЕҚ қайта ресімдеу',
                'title_en' => 'AVR reprocessing',
                'description_ru' => 'Сотрудник департамента судейства переоформляет АВР',
                'description_kk' => 'Төрешілік департаментінің қызметкері АЕҚ қайта ресімдейді',
                'description_en' => 'Refereeing department employee reprocesses AVR',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        // 5. Payment
        $operations['pay_5_1'] = Operation::updateOrCreate(
            ['value' => 'avr_confirmed_waiting_payment'],
            [
                'category_id' => $paymentCat->id,
                'title_ru' => 'АВР подтвержден. Ожидание оплаты',
                'title_kk' => 'АЕҚ расталды. Төлемді күту',
                'title_en' => 'AVR confirmed. Waiting payment',
                'description_ru' => 'АВР подтвержден, ожидается оплата бухгалтером',
                'description_kk' => 'АЕҚ расталды, бухгалтердің төлемін күтеді',
                'description_en' => 'AVR confirmed, waiting for accountant payment',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT],
                'is_first' => true,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        $operations['pay_5_2'] = Operation::updateOrCreate(
            ['value' => 'payment_completed'],
            [
                'category_id' => $paymentCat->id,
                'title_ru' => 'Оплата произведена',
                'title_kk' => 'Төлем жасалды',
                'title_en' => 'Payment completed',
                'description_ru' => 'Бухгалтер произвел оплату',
                'description_kk' => 'Бухгалтер төлем жасады',
                'description_en' => 'Accountant completed payment',
                'responsible_roles' => [RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT],
                'is_first' => false,
                'is_last' => false,
                'can_reject' => false,
                'is_active' => true,
                'result' => 0,
            ]
        );

        // 6. Final Result
        $operations['final_6_1'] = Operation::updateOrCreate(
            ['value' => 'successfully_completed'],
            [
                'category_id' => $finalResultCat->id,
                'title_ru' => 'Успешно завершен',
                'title_kk' => 'Сәтті аяқталды',
                'title_en' => 'Successfully completed',
                'description_ru' => 'Процесс успешно завершен',
                'description_kk' => 'Процесс сәтті аяқталды',
                'description_en' => 'Process successfully completed',
                'responsible_roles' => [],
                'is_first' => true,
                'is_last' => true,
                'can_reject' => false,
                'is_active' => true,
                'result' => 1,
            ]
        );

        // Second pass: Update relationships
        // 1. Referee Assignment
        $operations['ref_1_1']->update([
            'next_id' => $operations['ref_1_2']->id,
        ]);
        $operations['ref_1_2']->update([
            'previous_id' => $operations['ref_1_1']->id,
            'next_id' => $operations['trip_2_1']->id, // Continue to business trip
            'on_reject_id' => $operations['ref_1_3']->id,
        ]);
        $operations['ref_1_3']->update([
            'next_id' => $operations['ref_1_2']->id,
        ]);

        // 2. Business Trip
        $operations['trip_2_1']->update([
            'next_id' => $operations['trip_2_2']->id,
        ]);
        $operations['trip_2_2']->update([
            'previous_id' => $operations['trip_2_1']->id,
            'next_id' => $operations['trip_2_3']->id,
        ]);
        $operations['trip_2_3']->update([
            'previous_id' => $operations['trip_2_2']->id,
            'next_id' => $operations['trip_2_4']->id,
        ]);
        $operations['trip_2_4']->update([
            'previous_id' => $operations['trip_2_3']->id,
            'next_id' => $operations['trip_2_5']->id,
            'on_reject_id' => $operations['trip_2_8']->id,
        ]);
        $operations['trip_2_5']->update([
            'previous_id' => $operations['trip_2_4']->id,
            'next_id' => $operations['trip_2_6']->id,
            'on_reject_id' => $operations['trip_2_8']->id,
        ]);
        $operations['trip_2_6']->update([
            'previous_id' => $operations['trip_2_5']->id,
            'next_id' => $operations['trip_2_7']->id,
            'on_reject_id' => $operations['trip_2_8']->id,
        ]);
        $operations['trip_2_7']->update([
            'previous_id' => $operations['trip_2_6']->id,
            'next_id' => $operations['prot_3_1']->id, // Continue to match protocol
        ]);
        $operations['trip_2_8']->update([
            'next_id' => $operations['trip_2_4']->id,
        ]);

        // 3. Match Protocol
        $operations['prot_3_1']->update([
            'next_id' => $operations['prot_3_2']->id,
        ]);
        $operations['prot_3_2']->update([
            'previous_id' => $operations['prot_3_1']->id,
            'next_id' => $operations['prot_3_3']->id,
            'on_reject_id' => $operations['prot_3_4']->id,
        ]);
        $operations['prot_3_3']->update([
            'previous_id' => $operations['prot_3_2']->id,
            'next_id' => $operations['avr_4_1']->id, // Continue to AVR
            'on_reject_id' => $operations['prot_3_4']->id,
        ]);
        $operations['prot_3_4']->update([
            'next_id' => $operations['prot_3_2']->id,
        ]);

        // 4. AVR
        $operations['avr_4_1']->update([
            'next_id' => $operations['avr_4_2']->id,
        ]);
        $operations['avr_4_2']->update([
            'previous_id' => $operations['avr_4_1']->id,
            'next_id' => $operations['avr_4_3']->id,
        ]);
        $operations['avr_4_3']->update([
            'previous_id' => $operations['avr_4_2']->id,
            'next_id' => $operations['avr_4_4']->id,
            'on_reject_id' => $operations['avr_4_7']->id,
        ]);
        $operations['avr_4_4']->update([
            'previous_id' => $operations['avr_4_3']->id,
            'next_id' => $operations['avr_4_5']->id,
            'on_reject_id' => $operations['avr_4_7']->id,
        ]);
        $operations['avr_4_5']->update([
            'previous_id' => $operations['avr_4_4']->id,
            'next_id' => $operations['avr_4_6']->id,
            'on_reject_id' => $operations['avr_4_7']->id,
        ]);
        $operations['avr_4_6']->update([
            'previous_id' => $operations['avr_4_5']->id,
            'next_id' => $operations['pay_5_1']->id, // Continue to payment
            'on_reject_id' => $operations['avr_4_7']->id,
        ]);
        $operations['avr_4_7']->update([
            'next_id' => $operations['avr_4_2']->id,
        ]);

        // 5. Payment
        $operations['pay_5_1']->update([
            'next_id' => $operations['pay_5_2']->id,
        ]);
        $operations['pay_5_2']->update([
            'previous_id' => $operations['pay_5_1']->id,
            'next_id' => $operations['final_6_1']->id, // Continue to final result
        ]);
    }
}
