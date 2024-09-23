
$(document).ready(function () {
    console.log(timeRemaining);

    function startTimer(duration, display) {
        let timer = duration, minutes, seconds;
        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.text(minutes + ":" + seconds);

            if (--timer < 0) {
                timer = 0;
                $('#timeUpModal').modal('show');
            }
        }, 1000);
    }

    function renderQuestion() {
        let question = questions[currentQuestionIdx];
        $('#question-index').text(currentQuestionIdx + 1);
        $('#question-text').text(question.question);
        $('#options-container').empty();

        question.options.forEach(function(option, idx) {
            let checked = question.user_answer == option.id ? 'checked' : '';
            let optionHtml = `
                <div class="custom-control custom-radio">
                    <h5>
                        <input class="custom-control-input" type="radio" id="${option.id}" name="customRadio" value="${option.id}" data-question-id="${question.id}" ${checked}>
                        <label for="${option.id}" class="custom-control-label">${idx+1}. ${option.option_value}</label>
                    </h5>
                </div>
            `;
            $('#options-container').append(optionHtml);
        });

        updateSidebarButtons();
    }

    function updateSidebarButtons() {
        $('.question-button').each(function() {
            let index = $(this).data('index');
            if (index == currentQuestionIdx) {
                $(this).removeClass('btn-default').addClass('btn-primary');
            } else {
                $(this).removeClass('btn-primary').addClass('btn-default');
            }
        });
    }

    $('#nextBtn').on('click', function () {
        if (currentQuestionIdx < lastQuestionIdx) {
            currentQuestionIdx++;
            updateQuestionIndex(1);
        }
    });

    $('#prevBtn').on('click', function () {
        if (currentQuestionIdx > 0) {
            currentQuestionIdx--;
            updateQuestionIndex(-1);
        }
    });

    $('#endQuiz').on('click',function(){
        $.ajax({
            url: '/submit',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({ 
                quizId: quizId,
                userId:userId,
             }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (response) {
                console.log(response);
                // Redirect to the dashboard after the request is complete
                window.location.replace('/dashboard');
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

    $('#redirectButton').on('click', function() {
        window.location.replace("/dashboard");
    });

    $('.question-button').on('click', function () {
        var index = $(this).data('index');
        currentQuestionIdx = index;
        setQuestionIndex(index);
    });

    function updateQuestionIndex(increment) {
        $.ajax({
            url: '/update-question-index',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({ 
                increment: increment, 
                quizId: quizId,
                userId:userId,
             }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (response) {
                renderQuestion();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }

    function setQuestionIndex(index) {
        $.ajax({
            url: '/set-question-index',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({ 
                index: index,
                quizId: quizId,
                userId:userId, }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (response) {
                renderQuestion();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }

    $(document).on('change', '.custom-control-input', function() {
        var selectedOption = $(this).val();
        var questionId = $(this).data('question-id');

        $.ajax({
            url: '/update-answer',
            method: 'POST',
            data: {
                question_id: questionId,
                answer: selectedOption,
                quizId: quizId,
                userId:userId,
                _token: csrf_token
            },
            success: function(response) {
                if(response.status === 'success') {
                    questions[currentQuestionIdx].user_answer = selectedOption;
                }
            }
        });
    });

    let display = $('#timer');
    startTimer(timeRemaining, display);
    renderQuestion();
});