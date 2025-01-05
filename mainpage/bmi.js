  function editData(type) {
            const value = prompt(`새로운 ${type} 값을 입력해주세요:`);
            if (value) {
                document.getElementById("display" + type.charAt(0).toUpperCase() + type.slice(1)).textContent
 = value + (type === 'height' ? 'cm' : type === 'weight' ? 'kg' : '%');
                calculateBMI();
            }
        }

        function calculateBMI() {
            const height = parseFloat(document.getElementById('displayHeight').textContent.replace('cm', '')) / 100;  //키/100 = 1.7 
            const weight = parseFloat(document.getElementById('displayWeight').textContent.replace('kg', ''));  // 몸무게 / (미터키*미터키)
            const bmi = (weight / (height * height)).toFixed(1);
            document.getElementById('bmiResult').textContent = bmi;
        }
