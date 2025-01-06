import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.155.0/build/three.module.js';

let currentAnimation = null; // 현재 실행 중인 애니메이션
let scene, camera, renderer;

// 공통 초기화 함수
function initScene() {
    // 기존 scene, camera, renderer 초기화
    if (renderer) {
        renderer.dispose();
        document.body.removeChild(renderer.domElement);
    }

    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.body.appendChild(renderer.domElement);
}

// 눈 애니메이션
function startSnowAnimation() {
    initScene();
    camera.position.z = 10;

    const particleCount = 1000;
    const particlesGeometry = new THREE.BufferGeometry();
    const positions = [];

    for (let i = 0; i < particleCount; i++) {
        const x = (Math.random() - 0.5) * 20;
        const y = Math.random() * 20;
        const z = (Math.random() - 0.5) * 20;
        positions.push(x, y, z);
    }

    particlesGeometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    const particlesMaterial = new THREE.PointsMaterial({ color: 0xffffff, size: 0.2, transparent: true, opacity: 0.8 });
    const particles = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particles);

    const animate = () => {
        currentAnimation = animate;
        requestAnimationFrame(animate);
        const positions = particlesGeometry.attributes.position.array;

        for (let i = 0; i < positions.length; i += 3) {
            positions[i + 1] -= 0.05;
            if (positions[i + 1] < -10) {
                positions[i + 1] = 10;
            }
        }

        particlesGeometry.attributes.position.needsUpdate = true;
        renderer.render(scene, camera);
    };

    animate();
}

// 비 애니메이션
function startRainAnimation() {
    initScene();
    scene.fog = new THREE.Fog(0x000000, 1, 50);
    camera.position.set(0, 10, 20);

    const rainCount = 1000;
    const rainGeometry = new THREE.BufferGeometry();
    const positions = [];

    for (let i = 0; i < rainCount; i++) {
        positions.push(
            Math.random() * 50 - 25,
            Math.random() * 50,
            Math.random() * 50 - 25
        );
    }

    rainGeometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    const rainMaterial = new THREE.PointsMaterial({ color: 0xaaaaaa, size: 0.1, transparent: true });
    const rain = new THREE.Points(rainGeometry, rainMaterial);
    scene.add(rain);

    const thunderLight = new THREE.PointLight(0xffffff, 1, 100);
    thunderLight.position.set(0, 20, 0);
    scene.add(thunderLight);

    const animate = () => {
        currentAnimation = animate;
        requestAnimationFrame(animate);

        const positions = rainGeometry.attributes.position.array;
        for (let i = 1; i < positions.length; i += 3) {
            positions[i] -= 0.2;
            if (positions[i] < 0) {
                positions[i] = 50;
            }
        }

        rainGeometry.attributes.position.needsUpdate = true;

        if (Math.random() > 0.98) {
            thunderLight.intensity = 2 + Math.random() * 4;
            setTimeout(() => {
                thunderLight.intensity = 0;
            }, 100 + Math.random() * 200);
        }

        renderer.render(scene, camera);
    };

    animate();
}

// 불 애니메이션
function startFireAnimation() {
    initScene();
    camera.position.z = 5;

    const particleCount = 3000;
    const particlesGeometry = new THREE.BufferGeometry();
    const positions = [];
    const colors = [];
    const velocities = [];

    for (let i = 0; i < particleCount; i++) {
        const x = (Math.random() - 0.5) * 20;
        const y = Math.random() * 2 - 1;
        const z = (Math.random() - 0.5) * 20;
        positions.push(x, y, z);

        const color = new THREE.Color(`hsl(${Math.random() * 40}, 100%, 50%)`);
        colors.push(color.r, color.g, color.b);
        velocities.push(0, Math.random() * 0.05 + 0.01, 0);
    }

    particlesGeometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    particlesGeometry.setAttribute('color', new THREE.Float32BufferAttribute(colors, 3));

    const particlesMaterial = new THREE.PointsMaterial({
        size: 0.2,
        vertexColors: true,
        transparent: true,
        opacity: 0.8,
        blending: THREE.AdditiveBlending
    });

    const particles = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particles);

    const animate = () => {
        currentAnimation = animate;
        requestAnimationFrame(animate);

        const positions = particlesGeometry.attributes.position.array;
        const colors = particlesGeometry.attributes.color.array;

        for (let i = 0; i < positions.length; i += 3) {
            positions[i + 1] += velocities[i + 1] * 0.5;
            positions[i] *= 0.99;
            positions[i + 2] *= 0.99;

            if (positions[i + 1] > 10) {
                positions[i + 1] = -2;
                positions[i] = (Math.random() - 0.5) * 20;
                positions[i + 2] = (Math.random() - 0.5) * 20;

                const color = new THREE.Color(`hsl(${Math.random() * 40}, 100%, 50%)`);
                colors[i] = color.r;
                colors[i + 1] = color.g;
                colors[i + 2] = color.b;
            }
        }

        particlesGeometry.attributes.position.needsUpdate = true;
        particlesGeometry.attributes.color.needsUpdate = true;

        renderer.render(scene, camera);
    };

    animate();
}

// 애니메이션 변경 함수
function changeAnimation(type) {
    cancelAnimationFrame(currentAnimation); // 기존 애니메이션 중지
    switch (type) {
        case 'snow':
            startSnowAnimation();
            break;
        case 'rain':
            startRainAnimation();
            break;
        case 'fire':
            startFireAnimation();
            break;
    }
}

// 초기 애니메이션 실행
startSnowAnimation();

// 버튼으로 애니메이션 변경
document.addEventListener('keydown', (e) => {
    if (e.key === '1') changeAnimation('snow');
    if (e.key === '2') changeAnimation('rain');
    if (e.key === '3') changeAnimation('fire');
});
