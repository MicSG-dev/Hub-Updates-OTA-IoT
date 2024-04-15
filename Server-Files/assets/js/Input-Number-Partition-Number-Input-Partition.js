window.addEventListener('load', function () {
    let elementsPartitionInput = document.getElementsByClassName('partition_input');
    for (let i = 0; i < elementsPartitionInput.length; i++) {

        elementsPartitionInput[i].addEventListener('paste', function (event) {

            event.stopPropagation();
            event.preventDefault();
            var clipboardData, pastedData;
            
            clipboardData = event.clipboardData || window.clipboardData;
            pastedData = clipboardData.getData('Text').toUpperCase();


            function saoTodosNumerosEOuLetrasMaiusculas(texto) {
                let regex = /^[0-9A-Z]+$/;
                return regex.test(texto);
            }

            if (saoTodosNumerosEOuLetrasMaiusculas(pastedData)) {
                let identifier = event.target.dataset.number_partition_identifier;
                let groupIdentifier = event.target.dataset.number_partition_group_identifier;

                if (identifier == getElementsGroup()[groupIdentifier].length - pastedData.length) {
                    if (pastedData.length <= getElementsGroup()[groupIdentifier].length && pastedData.length > 0) {
                        let arrayNumbers = pastedData.split('');
                        let indexArray = identifier;
                        arrayNumbers.forEach(number => {
                            getElementsGroup()[groupIdentifier][indexArray].value = number;
                            getElementsGroup()[groupIdentifier][indexArray].focus();
                            indexArray++;
                        });
                    }
                }
            }




        });

        elementsPartitionInput[i].addEventListener('keypress', function (event) {
            event.preventDefault();
        });

        elementsPartitionInput[i].addEventListener('keydown', function (event) {

            if (
                (isLetterByEvent(event) || !isNumberByEvent(event)) &&
                event.key != "Tab" &&
                (event.key != "Ctrl" && event.key != "v" && event.key != "V")
            ) {
                event.preventDefault();
            }

        });
        elementsPartitionInput[i].addEventListener('keyup', function (event) {
            event.preventDefault();
            if (isNumberByEvent(event)) {

                let identifier = event.target.dataset.number_partition_identifier;
                let groupIdentifier = event.target.dataset.number_partition_group_identifier;
                getElementsGroup()[groupIdentifier][identifier].value = event.key;
                if (identifier < getElementsGroup()[groupIdentifier].length - 1) {
                    identifier++;
                }

                getElementsGroup()[groupIdentifier][identifier].focus();

            } else if (event.key == "F5") {
                window.location.reload();
            } else if (event.key == 'Backspace' || event.key == 'Delete') {

                let identifier = event.target.dataset.number_partition_identifier;
                let groupIdentifier = event.target.dataset.number_partition_group_identifier;
                getElementsGroup()[groupIdentifier][identifier].value = "";
                if (identifier > 0) {
                    identifier--;
                }
                getElementsGroup()[groupIdentifier][identifier].focus();
            } else if (isLetterByEvent(event) && event.key != 'v' && event.key != 'V' && !event.crtKey) {

                let letraEmMaiuscula = event.key.toUpperCase();
                let identifier = event.target.dataset.number_partition_identifier;
                let groupIdentifier = event.target.dataset.number_partition_group_identifier;
                getElementsGroup()[groupIdentifier][identifier].value = letraEmMaiuscula;

                if (identifier < getElementsGroup()[groupIdentifier].length - 1) {
                    identifier++;
                }

                getElementsGroup()[groupIdentifier][identifier].focus();


            }
        });

        elementsPartitionInput[i].addEventListener('focus', function (event) {
            let identifier = event.target.dataset.number_partition_identifier;
            let groupIdentifier = event.target.dataset.number_partition_group_identifier;

            getElementsGroup()[groupIdentifier][identifier].select();
        });
    }

    function getElementsGroup() {
        let elements = [];
        let lengthElementsOfList;
        let count = 0;
        while (lengthElementsOfList != 0) {
            let list = document.querySelectorAll('[data-number_partition_group_identifier="' + count + '"]');
            lengthElementsOfList = list.length;
            if (lengthElementsOfList != 0)
                elements.push(list);
            count++;
        }
        return elements;
    }

    function isNumberByEvent(event) {
        let response = false;
        for (let number = 0; number <= 9; number++) {
            if (event.key == number && event.key != " ") {
                response = true;
                break;
            }
        }
        return response;
    }

    function isLetterByEvent(event) {
        let response = false;
        if (event.key.toLowerCase() != event.key.toUpperCase() && event.key.length == 1) {
            response = true;
        } else {
            response = false;
        }
        return response;
    }
});