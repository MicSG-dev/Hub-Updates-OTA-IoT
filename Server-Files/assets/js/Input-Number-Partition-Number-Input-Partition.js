window.addEventListener('load', function () {
    let elementsPartitionInput = document.getElementsByClassName('partition_input');
    for (let i = 0; i < elementsPartitionInput.length; i++) {

        elementsPartitionInput[i].addEventListener('paste', function (event) {
            event.stopPropagation();
            event.preventDefault();
            var clipboardData, pastedData;
            // Get pasted data via clipboard API
            clipboardData = event.clipboardData || window.clipboardData;
            pastedData = clipboardData.getData('Text');

            // Do whatever with pasteddata

            function saoTodosNumeros(texto) {
                return /^\d+$/.test(texto);
            }

            if (saoTodosNumeros(pastedData)) {
                let identifier = event.target.dataset.number_partition_identifier;
                let groupIdentifier = event.target.dataset.number_partition_group_identifier;

                if (identifier == 5 - pastedData.length) {
                    if (pastedData.length <= 5 && pastedData.length > 0) {
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

        elementsPartitionInput[i].addEventListener('keyup', function (event) {
            if (isNumberByEvent(event)) {
                let identifier = event.target.dataset.number_partition_identifier;
                let groupIdentifier = event.target.dataset.number_partition_group_identifier;

                if (identifier < getElementsGroup()[groupIdentifier].length - 1) {
                    identifier++;
                }

                getElementsGroup()[groupIdentifier][identifier].focus();

            } else if (event.key == "F5") {
                window.location.reload();
            } else if (event.key == 'Backspace') {


                let identifier = event.target.dataset.number_partition_identifier;
                let groupIdentifier = event.target.dataset.number_partition_group_identifier;
                getElementsGroup()[groupIdentifier][identifier].value = "";
                if (identifier > 0) {
                    identifier--;
                }
                getElementsGroup()[groupIdentifier][identifier].focus();
            }
            else {

                event.preventDefault();
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
});