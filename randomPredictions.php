<?php

//Выводит случайное предсказание тремя способами. С помощью PHP, JS, AJAX

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle(GetMessage("COMPANY_TITLE"));
$APPLICATION->AddChainItem(GetMessage("COMPANY_TITLE"));

//PHP
$predictions = [
    '«Если тебе плюют в спину, значит ты впереди» (Конфуций).',
    '«Меняйся! Не меняются только глупые» (Конфуций).',
    '«Не считай себя умнее всех, иначе гордыней наполнится душа, и будешь ты беспомощен перед врагами» (Антоний Великий).',
    '«Долг власть имущих — спасать, а не губить» (Тихон Задонский).',
    '«Не все рви, что растет» (Козьма Прутков).',
    '«Если хочешь быть счастливым, будь им» (Козьма Прутков).',
    '«Всегда держись начеку!» (Козьма Прутков).',
    '«Гони любовь хоть в дверь, она влетит в окно!» (Козьма Прутков).',
    '«Нет полных женщин — есть тесная одежда» (Фаина Раневская).',
    '«Жить надо так, чтобы тебя помнили даже сволочи» (Фаина Раневская).',
    'Всем желайте счастья, мира! Светит вам своя квартира!',
    'Сохранить желаю стиль! Будет вам автомобиль!',
    'И опять у вас везение! Ожидайте прибавления!',
    'Если одеться в костюм наизнанку, в командировку отправят в загранку.',
    'Летом на нудистском пляже ваше счастье рядом ляжет.',
    'Не тревожит пусть забота! Ждет вас новая работа!',
    'Не скучать желаю зря, в помощь новые друзья!',
    'Если вас клюнет вареный индюк — ждите в подарок крутой ноутбук!',
    'За здоровье папы тост! Будет вам карьерный рост!',
    'Сегодня ждет тебя удача, а завтра новенькая дача.',
    'Ваши планы скоро сбудутся.',
    'Осуществить заветное поможет друг.',
    'Вас ждет романтическое приключение.',
    'Пора в отпуск.',
    'Завтра вас ожидает сюрприз.',
    'Время — ваш лучший союзник.',
    'Решение отложите до завтра.',
    'Съешьте еще 1 печенье, в нем и найдете ответ.',
    'Терпение! Все получится!',
    'Будьте настойчивее, задуманное почти в руках!',
];

if (isset($_POST['predictionGenerator'])){
    $key = array_rand($predictions, 1);
    $prediction = $predictions[$key];
    echo $prediction;
}

?>

<div>
    <form method="POST">
        <input name="predictionGenerator" type="submit" value="Получить предсказание" />
    </form>
</div>


<!--JavaScript-->
<script>
    let predictions = [
        '«Если тебе плюют в спину, значит ты впереди» (Конфуций).',
        '«Меняйся! Не меняются только глупые» (Конфуций).',
        '«Не считай себя умнее всех, иначе гордыней наполнится душа, и будешь ты беспомощен перед врагами» (Антоний Великий).',
        '«Долг власть имущих — спасать, а не губить» (Тихон Задонский).',
        '«Не все рви, что растет» (Козьма Прутков).',
        '«Если хочешь быть счастливым, будь им» (Козьма Прутков).',
        '«Всегда держись начеку!» (Козьма Прутков).',
        '«Гони любовь хоть в дверь, она влетит в окно!» (Козьма Прутков).',
        '«Нет полных женщин — есть тесная одежда» (Фаина Раневская).',
        '«Жить надо так, чтобы тебя помнили даже сволочи» (Фаина Раневская).',
        'Всем желайте счастья, мира! Светит вам своя квартира!',
        'Сохранить желаю стиль! Будет вам автомобиль!',
        'И опять у вас везение! Ожидайте прибавления!',
        'Если одеться в костюм наизнанку, в командировку отправят в загранку.',
        'Летом на нудистском пляже ваше счастье рядом ляжет.',
        'Не тревожит пусть забота! Ждет вас новая работа!',
        'Не скучать желаю зря, в помощь новые друзья!',
        'Если вас клюнет вареный индюк — ждите в подарок крутой ноутбук!',
        'За здоровье папы тост! Будет вам карьерный рост!',
        'Сегодня ждет тебя удача, а завтра новенькая дача.',
        'Ваши планы скоро сбудутся.',
        'Осуществить заветное поможет друг.',
        'Вас ждет романтическое приключение.',
        'Пора в отпуск.',
        'Завтра вас ожидает сюрприз.',
        'Время — ваш лучший союзник.',
        'Решение отложите до завтра.',
        'Съешьте еще 1 печенье, в нем и найдете ответ.',
        'Терпение! Все получится!',
        'Будьте настойчивее, задуманное почти в руках!',
    ];

    function predictionGenerator() {
        let id = Math.floor(Math.random() * predictions.length);
        let prediction = predictions[id];
        document.getElementById("export").innerHTML = prediction;
    }

</script>

<div>
    <form>
        <input type="button" value="Получить предсказание" onclick="predictionGenerator()" />
    </form>
    <p id="export"></p>
</div>


<!--AJAX REQUEST-->
<button id="my-button">send ajax request</button>
<p id="data"></p>

<script>
    const button = BX('my-button')

    BX.bind(button, 'click', () => {
        BX.ajax({
            url: '/local/PavelFunc.php',
            method: 'POST',
            dataType: 'json',
            timeout: 10,
            onsuccess: function( res ) {
                document.getElementById("data").innerHTML = res;
            },
        })
    })
</script>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
