(function($) {
    var QuestionFormat = function() {
        this.$formatContainer = $('#question-format-container');
        this.$addFormatButton = $('#add-format');
        this.$showSummarySelect = $('#show_summary_display');
        this.$resultDeclaredOn = $('#result_declared_on');
        this.$isShowSummaryAllowed = $('#is_show_summary_allowed');
        this.$assingQuizNow = $('#assign_quiz_now');
        this.$showSummaryForContainer = $('#show-summary-for-container');
        this.$showSummaryDisplayContainer = $('#show-summary-display-container');
        this.$assingQuizToContainer = $('#assign-quiz-to-container');
        this.$selectBatches = $('#select_batches');
        this.$selectStudents = $('#select_students');
        this.$selectAssignBatches = $('#assign_to_batches_select');
        this.$selectAssignStudents = $('#assign_to_students_select');
        this.$batchesSelect = $('#batches');
        this.$studentsSelect = $('#students');
        this.$assignToBatches = $('#assign_to_batches');
        this.$assignToStudents = $('#assign_to_students');
        this.$quizForm = $('#QuizFRM');
        

        this.init();
    };

    QuestionFormat.prototype = {
        init: function() {
            var self = this;

            $(document).ready(function() {
                self.$addFormatButton.on('click', function() {
                    self.addFormat();
                });

                self.toggleResultDeclaredOn();
                self.$showSummarySelect.on('change', function() {
                    self.toggleResultDeclaredOn();
                });

                self.toggleSummaryOptions();
                self.$isShowSummaryAllowed.on('change', function() {
                    self.toggleSummaryOptions();
                });

                self.toggleAssignQuizOptions();
                self.$assingQuizNow.on('change', function() {
                    self.toggleAssignQuizOptions();
                });

                self.toggleSelectionDivs();
                $('input[name="show_summary_for"]').on('change', function() {
                    self.toggleSelectionDivs();
                });

                self.toggleAssignSelectionDivs();
                $('input[name="assign_quiz_to"]').on('change', function() {
                    self.toggleAssignSelectionDivs();
                });

                self.$quizForm.on('submit', function(e) {
                    if (!self.validateForm()) {
                        e.preventDefault();
                    }
                });
            });

            this.$formatContainer.on('click', '.delete-format', function() {
                $(this).closest('.question-format-item').remove();
            });
        },

        addFormat: function() {
            var $formatContainer = $('<div>').addClass('question-format-item');

            var $marksLabel = $('<label>').html('Marks:');
            var $marksInput = $('<input>').attr({
                type: 'number',
                name: 'marks[]',
                required: true
            }).addClass('form-control');

            var $questionsLabel = $('<label>').html(' Questions: ').addClass('ml-2');
            var $questionsInput = $('<input>').attr({
                type: 'number',
                name: 'questions[]',
                required: true
            }).addClass('form-control');

            var $deleteButton = $('<button>').attr({
                type: 'button'
            }).text('Delete').addClass('btn btn-danger delete-format');

            $formatContainer.append($marksLabel, $marksInput, $questionsLabel, $questionsInput, $deleteButton);
            this.$formatContainer.append($formatContainer);
        },

        toggleResultDeclaredOn: function() {
            if (this.$showSummarySelect.val() === 'instant') {
                this.$resultDeclaredOn.hide();
            } else {
                this.$resultDeclaredOn.show();
            }
        },

        toggleSummaryOptions: function() {
            if (this.$isShowSummaryAllowed.is(':checked')) {
                this.$showSummaryForContainer.show();
                this.$showSummaryDisplayContainer.show();
            } else {
                this.$showSummaryForContainer.hide();
                this.$showSummaryDisplayContainer.hide();
                this.$showSummarySelect.val('instant'); // Clear show summary display
                this.$resultDeclaredOn.find('input').val(null); // Clear result declared on
                this.$showSummaryForContainer.find('label').removeClass('active').trigger('change'); // remove active class from label
                $('input[name="show_summary_for"]').prop('checked', false).trigger('change');; // Uncheck all show_summary_for checkboxes+
                this.$batchesSelect.val(null).trigger('change'); // Clear summary batches
                this.$studentsSelect.val(null).trigger('change'); // Clear summary students
                this.toggleResultDeclaredOn();
            }
        },

        toggleAssignQuizOptions: function() {
            if(this.$assingQuizNow.is(':checked')) {
                this.$assingQuizToContainer.show();
            }
            else{
                this.$assingQuizToContainer.hide();
                this.$selectAssignStudents.val(null).trigger('change');
                this.$selectAssignBatches.val(null).trigger('change');
                this.$assingQuizToContainer.find('label').removeClass('active').trigger('change'); // remove active class from label
                $('input[name="assign_quiz_to"]').prop('checked', false).trigger('change');; // Uncheck all show_summary_for checkboxes+
            }
        },  

        toggleSelectionDivs: function() {
            if ($('#select_batches_radio').is(':checked')) {
                this.$selectBatches.show();
                this.$selectStudents.hide(); 
                this.$studentsSelect.val(null).trigger('change');
                this.$selectStudents.find('input[type="checkbox"]').prop('checked', false);
            } else if ($('#select_students_radio').is(':checked')) {
                this.$selectBatches.hide();
                this.$selectStudents.show();
                this.$batchesSelect.val(null).trigger('change');
                this.$selectStudents.find('input[type="checkbox"]').prop('checked', false);
            } else if ($('#select_all_radio').is(':checked')) {
                this.$selectBatches.hide();
                this.$batchesSelect.val(null).trigger('change');
                this.$studentsSelect.val(null).trigger('change');
            } else {
                this.$selectBatches.hide();
                this.$selectStudents.hide();
            }
        },

        toggleAssignSelectionDivs: function() {
            if ($('#select_assign_to_batches_radio').is(':checked')) {
                this.$assignToBatches.show();
                this.$assignToStudents.hide();
                this.$selectAssignStudents.val(null).trigger('change');
            } else if ($('#select_assign_to_students_radio').is(':checked')) {
                this.$assignToBatches.hide();
                this.$assignToStudents.show();
                this.$selectAssignBatches.val(null).trigger('change');
            } else {
                this.$assignToBatches.hide();
                this.$assignToStudents.hide();
            }
        },
        validateForm: function() {
            var isValid = true;
            if (this.$showSummarySelect.val() === 'after_result' && !this.$resultDeclaredOn.find('input').val()) {
                isValid = false;
                alert('Result declared on date cannot be empty when Show summary is set to After result.');
            }
            return isValid;
        }
    };

    // Instantiate the QuestionFormat object when the document is ready
    $(document).ready(function() {
        new QuestionFormat();
        $('.select2').select2();

        // Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        // Initialize datetime pickers
        $('#scheduleddate, #resultdate, #startsat, #ended_at, #rejointill').datetimepicker({
            icons: { time: 'far fa-clock' }
        });
    });
})(jQuery);
