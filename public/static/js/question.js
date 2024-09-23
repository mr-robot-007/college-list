(function($) {
    var Questions = function() {
        this.optionCount = 1;
        this.$optionsContainer = $('#options-container');
        this.$addOptionButton = $('#add-option');
        this.$questionForm = $('#question-form');
        this.$correctAnswerSelect = $('#correct-answer');
        this.correctOption = $('#correct-option').val();
        
        this.init();
    };

    Questions.prototype = {
        init: function() {
            var self = this;
            
            $(document).ready(function() {
                self.updateCorrectAnswerOptions();
                self.$addOptionButton.on('click', function() {
                    self.addOption();
                    self.updateCorrectAnswerOptions();
                });
                self.setCorrectOption();
            });

            this.$optionsContainer.on('click', '.delete-option', function() {
                $(this).closest('.option-container').remove();
                self.updateCorrectAnswerOptions();
            });
        },

        addOption: function() {
            var self = this;
            
            var $optionContainer = $('<div>').addClass('option-container');

            var $optionLabel = $('<label>').html('Option <span class="childAutoCounter">&nbsp;</span>:');
            var $optionInput = $('<input>').attr({
                type: 'text',
                name: 'options[]',
                required: true
            }).addClass('form-control');

            var $sequenceLabel = $('<label>').html(' Sequence: ').addClass('ml-2');
            var $sequenceInput = $('<input>').attr({
                type: 'number',
                name: 'sequence[]',
                required: true
            }).addClass('form-control');

            var $deleteButton = $('<button>').attr({
                type: 'button'
            }).text('Delete').addClass('btn btn-danger delete-option');

            $optionContainer.append($optionLabel, $optionInput, $sequenceLabel, $sequenceInput, $deleteButton);
            this.$optionsContainer.append($optionContainer);

            this.optionCount++;
        },

        updateCorrectAnswerOptions: function() {
            var self = this;

            // Clear all current options
            this.$correctAnswerSelect.empty();

            // Regenerate options based on current inputs
            this.$optionsContainer.find('.option-container').each(function(index, container) {
                var $option = $('<option>').attr('value', index + 1).text('Option ' + (index + 1));
                self.$correctAnswerSelect.append($option);
            });

            // Set the correct option if it's defined
            this.setCorrectOption();
        },

        setCorrectOption: function() {
            if (this.correctOption >= 0) {
                this.$correctAnswerSelect.val(parseInt(this.correctOption) + 1);
            }
        }
    };

    // Instantiate the Questions object when the document is ready
    $(document).ready(function() {
        var questionsOBJ = new Questions();
    });
})(jQuery);
