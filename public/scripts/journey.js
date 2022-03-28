'use strict';

(
    function () {
        function show_saved_query_message() {
            $('#saved_journeys_message')
                .text(
                    'Number of saved journeys: '
                    + ((localStorage.getItem('journeys') ?? '').match(/\n/g) ?? []).length
                );
        }

        document.addEventListener(
            'DOMContentLoaded'
            , function () {
                ['boarding_time', 'alighting_time'].forEach(
                    function (time_field_id) {
                        const button_id = time_field_id + '_button';
                        const offset_field_id = time_field_id + '_offset';

                        function get_offset() {
                            return (new Date).getTimezoneOffset() / -60;
                        }

                        document.getElementById(offset_field_id).value = get_offset();
                        document.getElementById(button_id).addEventListener(
                            'click'
                            , function () {
                                function format_date(date) {
                                    const year = date.getFullYear();
                                    const month = date.getMonth() + 1;
                                    const day = date.getDate();
                                    const hour = date.getHours();
                                    const minute = date.getMinutes();

                                    return year
                                        + '-'
                                        + (month < 10 ? '0' : '')
                                        + month
                                        + '-'
                                        + (day < 10 ? '0' : '')
                                        + day
                                        + 'T'
                                        + (hour < 10 ? '0' : '')
                                        + hour
                                        + ':'
                                        + (minute < 10 ? '0' : '')
                                        + minute;
                                }

                                document.getElementById(time_field_id).value
                                    = format_date(new Date());
                                document.getElementById(offset_field_id).value
                                    = get_offset();
                            }
                        )
                    }
                );
                show_saved_query_message();
            }
        );

        $(document).ready(
            function () {
                $('#ticket_table select').change(
                    function () {
                        $(this).siblings('details').find('input')
                            .prop('disabled', this.value !== '')
                    }
                );
                const $ticket_description_inputs = $('#ticket_table input.ticket_description');
                $('#submit_button,#hidden_submit_button').click(
                    function () {
                        $('#journey_table [data-required="required"]').attr('required', 'required');
                        $ticket_description_inputs
                            .filter((_, element) => (element.value !== ''))
                            .parent()
                            .parent()
                            .siblings()
                            .find('[data-required="ticket"]')
                            .attr('required', 'required');
                        $ticket_description_inputs
                            .filter((_, element) => (element.value === ''))
                            .parent()
                            .parent()
                            .siblings()
                            .find('[data-required="ticket"]')
                            .removeAttr('required')
                    }
                );
                $('#last_button').click(
                    function () {
                        $('#journey_table [data-required]').removeAttr('required');
                        $('#ticket_table [data-required]').removeAttr('required');
                    }
                );
                const $form = $('#form');
                $('#push_button').click(
                    function () {
                        const serialised_data = localStorage.getItem('journeys') ?? '';
                        localStorage.setItem('journeys', serialised_data + $form.serialize() + "\n");
                        show_saved_query_message();
                        $form[0].reset();
                    }
                );
                $('#pop_button').click(
                    function () {
                        const serialised_data = localStorage.getItem('journeys') ?? '';
                        const index = serialised_data.indexOf("\n");
                        if (index === -1) {
                            alert('Queue is empty.');
                            return false;
                        }
                        const entry = serialised_data.substr(0, index);
                        localStorage.setItem('journeys', serialised_data.substring(index + 1));
                        show_saved_query_message();
                        $("input[type='checkbox']").prop('checked', false);
                        $form.deserialize(entry);
                        return null;
                    }
                );
            }
        );
    }
)();
