<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>랭킹</title>
    <link rel="stylesheet" href="ranking.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/three@0.155.0/build/three.module.js"></script>
</head>

<body>
    <?php
    // PHP에서 rank 값을 가져오기
    require 'rankAndScore.php';
    ?>
    
    <canvas id="bg-canvas"></canvas>

    <script type="module">
        import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.155.0/build/three.module.js';

        let currentAnimation = null;
        let currentAnimationFrameId = null;
        let scene, camera, renderer;

        // 공통 초기화 함수
        function initScene() {
            if (renderer) {
                renderer.dispose();
                document.body.removeChild(renderer.domElement);
            }
            scene = new THREE.Scene();
            camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            document.body.appendChild(renderer.domElement);
        }

        // 애니메이션 관리 객체
        const animations = {
            snow: { // 눈
                start: () => {
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
                    const particlesMaterial = new THREE.PointsMaterial({
                        color: 0xffffff,
                        size: 0.2,
                        transparent: true,
                        opacity: 0.8,
                    });
                    const particles = new THREE.Points(particlesGeometry, particlesMaterial);
                    scene.add(particles);

                    const animate = () => {
                        currentAnimationFrameId = requestAnimationFrame(animate);
                        const positions = particlesGeometry.attributes.position.array;

                        for (let i = 0; i < positions.length; i += 3) {
                            positions[i + 1] -= 0.05; // Y축 이동
                            if (positions[i + 1] < -10) {
                                positions[i + 1] = 10; // 바닥으로 내려가면 위로 리셋
                            }
                        }

                        particlesGeometry.attributes.position.needsUpdate = true;
                        renderer.render(scene, camera);
                    };

                    animate();
                },
                stop: () => cancelAnimationFrame(currentAnimationFrameId),
            },
            rain: { // 비
                start: () => {
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
                    const rainMaterial = new THREE.PointsMaterial({
                        color: 0xaaaaaa,
                        size: 0.1,
                        transparent: true,
                    });
                    const rain = new THREE.Points(rainGeometry, rainMaterial);
                    scene.add(rain);

                    const animate = () => {
                        currentAnimationFrameId = requestAnimationFrame(animate);

                        const positions = rainGeometry.attributes.position.array;
                        for (let i = 1; i < positions.length; i += 3) {
                            positions[i] -= 0.2; // Y축 이동
                            if (positions[i] < 0) {
                                positions[i] = 50; // 바닥으로 내려가면 위로 리셋
                            }
                        }

                        rainGeometry.attributes.position.needsUpdate = true;
                        renderer.render(scene, camera);
                    };

                    animate();
                },
                stop: () => cancelAnimationFrame(currentAnimationFrameId),
            },
            fire: { // 불
                start: () => {
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
                        blending: THREE.AdditiveBlending,
                    });

                    const particles = new THREE.Points(particlesGeometry, particlesMaterial);
                    scene.add(particles);

                    const animate = () => {
                        currentAnimationFrameId = requestAnimationFrame(animate);

                        const positions = particlesGeometry.attributes.position.array;
                        const colors = particlesGeometry.attributes.color.array;

                        for (let i = 0; i < positions.length; i += 3) {
                            positions[i + 1] += velocities[i + 1] * 0.5; // 상승
                            positions[i] *= 0.99; // X축 감속
                            positions[i + 2] *= 0.99; // Z축 감속

                            if (positions[i + 1] > 10) {
                                positions[i + 1] = -2; // 높이를 초기화
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
                },
                stop: () => cancelAnimationFrame(currentAnimationFrameId),
            },
            sun: { // 태양
                start: () => {
                    initScene();
                    scene.background = new THREE.Color(0x87CEEB); // 하늘색 배경
                    camera.position.set(0, 20, 100);

                    const sunGeometry = new THREE.SphereGeometry(15, 32, 32);
                    const sunMaterial = new THREE.MeshBasicMaterial({ color: 0xFFA500 });
                    const sun = new THREE.Mesh(sunGeometry, sunMaterial);
                    sun.position.set(0, 50, -100);
                    scene.add(sun);

                    const sunGlowGeometry = new THREE.SphereGeometry(20, 32, 32);
                    const sunGlowMaterial = new THREE.MeshBasicMaterial({
                        color: 0xFFFF00,
                        transparent: true,
                        opacity: 0.4,
                    });
                    const sunGlow = new THREE.Mesh(sunGlowGeometry, sunGlowMaterial);
                    sunGlow.position.copy(sun.position);
                    scene.add(sunGlow);

                    const animate = () => {
                        currentAnimationFrameId = requestAnimationFrame(animate);

                        // 태양 광채 효과
                        const scale = 1 + 0.05 * Math.sin(Date.now() * 0.002);
                        sunGlow.scale.set(scale, scale, scale);

                        renderer.render(scene, camera);
                    };

                    animate();
                },
                stop: () => cancelAnimationFrame(currentAnimationFrameId),
            },
            firecracker: { // 폭죽
                start: () => {
                    initScene();
                    camera.position.z = 50;

                    const particleCount = 500;
                    const particlesGeometry = new THREE.BufferGeometry();
                    const positions = [];
                    const velocities = [];
                    const colors = [];

                    for (let i = 0; i < particleCount; i++) {
                        positions.push(0, 0, 0);
                        velocities.push(
                            (Math.random() - 0.5) * 10,
                            Math.random() * 10 + 5,
                            (Math.random() - 0.5) * 10
                        );
                        const color = new THREE.Color(`hsl(${Math.random() * 360}, 100%, 50%)`);
                        colors.push(color.r, color.g, color.b);
                    }

                    particlesGeometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
                    particlesGeometry.setAttribute('color', new THREE.Float32BufferAttribute(colors, 3));

                    const particlesMaterial = new THREE.PointsMaterial({
                        size: 0.3,
                        vertexColors: true,
                        transparent: true,
                    });

                    const particles = new THREE.Points(particlesGeometry, particlesMaterial);
                    scene.add(particles);

                    const animate = () => {
                        currentAnimationFrameId = requestAnimationFrame(animate);

                        const positions = particlesGeometry.attributes.position.array;
                        const colors = particlesGeometry.attributes.color.array;

                        for (let i = 0; i < positions.length; i += 3) {
                            positions[i] += velocities[i];     // X축 이동
                            positions[i + 1] += velocities[i + 1]; // Y축 이동
                            positions[i + 2] += velocities[i + 2]; // Z축 이동

                            velocities[i + 1] -= 0.1; // 중력 효과

                            const distance = Math.sqrt(
                                positions[i] ** 2 +
                                positions[i + 1] ** 2 +
                                positions[i + 2] ** 2
                            );
                            colors[i] *= 1 - distance * 0.01;
                            colors[i + 1] *= 1 - distance * 0.01;
                            colors[i + 2] *= 1 - distance * 0.01;
                        }

                        particlesGeometry.attributes.position.needsUpdate = true;
                        particlesGeometry.attributes.color.needsUpdate = true;
                        renderer.render(scene, camera);
                    };

                    animate();
                },
                stop: () => cancelAnimationFrame(currentAnimationFrameId),
            },
        };

        // 애니메이션 로드 함수
        function loadAnimation(type) {
            if (currentAnimation && animations[currentAnimation]) {
                animations[currentAnimation].stop();
            }
            currentAnimation = type;
            animations[type].start();
        }
        <?php $userRank; ?>
        // PHP에서 데이터를 가져와 초기 애니메이션 설정
        async function initializeAnimation() {
            try {
                const rank = <?php echo json_encode($userRank); ?>;
                console.log('Rank:', rank);

                if (rank < 10) {
                    loadAnimation('fire');
                } else if (rank < 40) {
                    loadAnimation('sun');
                } else if (rank < 60) {
                    loadAnimation('rain');
                } else {
                    loadAnimation('snow');
                }
            } catch (error) {
                console.error('Rank fetch error:', error);
            }
        }

        // 초기화
        initializeAnimation();

        // 창 크기 변경 처리
        window.addEventListener('resize', () => {
            renderer.setSize(window.innerWidth, window.innerHeight);
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
        });
    </script>
</body>

</html>