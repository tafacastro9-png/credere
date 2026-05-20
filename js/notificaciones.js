   $(document).ready(function() {
                                    function loadNotifications() {
                                        $.ajax({
                                            url: '../includes/verificarNotification.php',
                                            method: 'POST',
                                            success: function(response) {
                                                $('#notificationContent').html(response);
                                            }
                                        });
                                    }

                                    function updateNotificationCount() {
                                        $.ajax({
                                            url: '../includes/contarNotification.php',
                                            type: 'GET',
                                            success: function(response) {
                                                $('#count-label').text(response);
                                                if (response === '0') {
                                                    $('#count-label').hide();
                                                } else {
                                                    $('#count-label').show();
                                                }
                                            }
                                        });
                                    }

                                    loadNotifications();
                                    updateNotificationCount();

                                    setInterval(function() {
                                        loadNotifications();
                                        updateNotificationCount();
                                    }, 5000);
                                });