import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

window.THREE = THREE;
window.OrbitControls = OrbitControls;

document.addEventListener('alpine:init', () => {
    // ── Background SVG Animation (Storefront) ──────────────────────────────
    Alpine.data('storefrontAmbient', () => ({
        init() {
            const svg = this.$el;
            let width = window.innerWidth;
            let height = window.innerHeight;
            let animationFrameId = null;

            const handleResize = () => {
                width = window.innerWidth;
                height = window.innerHeight;
            };
            window.addEventListener('resize', handleResize);

            const petalPaths = [
                "M 0 -15 C 10 -25, 20 -10, 10 5 C 0 20, -15 10, 0 -15",
                "M 0 -25 C 10 -15, 5 15, 0 25 C -5 15, -10 -15, 0 -25",
                "M 0 -10 C 5 -15, 15 -15, 10 -5 C 15 0, 15 10, 5 5 C 0 15, -10 10, -5 0 C -10 -5, -5 -15, 0 -10 Z",
                "M 0 15 C 2 -5, 10 -15, 8 -28 C 0 -18, -4 -5, 0 15 M 4 0 C 9 -5, 12 -11, 12 -11 M -2 -5 C -7 -9, -9 -14, -9 -14"
            ];

            const particles = [];
            const particleCount = Math.min(45, Math.max(16, Math.floor((width * height) / 55000)));

            for (let i = 0; i < particleCount; i++) {
                const group = document.createElementNS("http://www.w3.org/2000/svg", "g");
                const path = document.createElementNS("http://www.w3.org/2000/svg", "path");

                const pathD = petalPaths[Math.floor(Math.random() * petalPaths.length)];
                path.setAttribute("d", pathD);
                path.setAttribute("stroke", "var(--brand-accent)");
                path.setAttribute("stroke-dasharray", Math.random() > 0.85 ? "2,2" : "none");
                path.setAttribute("stroke-width", Math.random() * 0.8 + 0.8);
                path.setAttribute("stroke-opacity", Math.random() * 0.35 + 0.35);
                path.setAttribute("fill", "var(--brand-accent)");
                path.setAttribute("fill-opacity", Math.random() * 0.08 + 0.06);

                group.style.position = "absolute";
                group.style.transformOrigin = "center";
                group.style.transformBox = "fill-box";
                group.appendChild(path);
                svg.appendChild(group);

                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    baseVx: Math.random() * 0.24 - 0.12,
                    baseVy: -(Math.random() * 0.28 + 0.12),
                    vx: 0,
                    vy: 0,
                    angle: Math.random() * 360,
                    rotationSpeed: (Math.random() * 0.15 + 0.05) * (Math.random() > 0.5 ? 1 : -1),
                    scale: Math.random() * 0.6 + 0.65,
                    opacity: Math.random() * 0.3 + 0.5,
                    swaySpeed: Math.random() * 0.005 + 0.002,
                    swayAmount: Math.random() * 0.4 + 0.1,
                    phase: Math.random() * 100,
                    element: group
                });
            }

            const mouse = { x: -2000, y: -2000, active: false };
            const handleMouseMove = (e) => {
                mouse.x = e.clientX;
                mouse.y = e.clientY;
                mouse.active = true;
            };
            const handleMouseLeave = () => {
                mouse.active = false;
            };
            window.addEventListener('mousemove', handleMouseMove);
            window.addEventListener('mouseleave', handleMouseLeave);

            let lastScrollY = window.scrollY;
            let scrollVelocity = 0;
            let scrollTimeout = null;

            const handleScroll = () => {
                const currentScrollY = window.scrollY;
                const delta = currentScrollY - lastScrollY;
                scrollVelocity = delta;
                lastScrollY = currentScrollY;

                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    scrollVelocity = 0;
                }, 100);
            };
            window.addEventListener('scroll', handleScroll, { passive: true });

            let isVisible = true;
            const handleVisibilityChange = () => {
                isVisible = !document.hidden;
                if (isVisible && !animationFrameId) {
                    lastScrollY = window.scrollY;
                    animate();
                }
            };
            document.addEventListener('visibilitychange', handleVisibilityChange);

            let frameCount = 0;
            const animate = () => {
                if (!isVisible) {
                    animationFrameId = null;
                    return;
                }
                frameCount++;
                scrollVelocity *= 0.94;
                if (Math.abs(scrollVelocity) < 0.05) scrollVelocity = 0;

                particles.forEach((p) => {
                    const sway = Math.sin(p.phase + frameCount * p.swaySpeed) * p.swayAmount;
                    p.vx = p.baseVx + sway;
                    p.vy = p.baseVy;

                    const scrollEffectY = -scrollVelocity * 0.18;
                    const scrollEffectX = scrollVelocity * 0.04 * Math.sin(p.phase + frameCount * 0.015);

                    let pushX = 0;
                    let pushY = 0;
                    if (mouse.active) {
                        const dx = p.x - mouse.x;
                        const dy = p.y - mouse.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 180) {
                            const force = (180 - dist) / 180;
                            const pushAngle = Math.atan2(dy, dx);
                            pushX = Math.cos(pushAngle) * force * 1.8;
                            pushY = Math.sin(pushAngle) * force * 1.8;
                        }
                    }

                    p.x += p.vx + scrollEffectX + pushX;
                    p.y += p.vy + scrollEffectY + pushY;
                    p.angle += p.rotationSpeed + (scrollVelocity * 0.08);

                    const rotateX = Math.sin(p.phase + frameCount * p.swaySpeed * 1.4) * 35;
                    const rotateY = Math.cos(p.phase * 0.8 + frameCount * p.swaySpeed * 1.1) * 35;

                    const windTiltX = Math.max(-75, Math.min(75, rotateX + (scrollVelocity * 0.5)));
                    const windTiltY = Math.max(-75, Math.min(75, rotateY + (scrollVelocity * 0.15)));

                    const margin = 60;
                    if (p.x < -margin) {
                        p.x = width + margin;
                    } else if (p.x > width + margin) {
                        p.x = -margin;
                    }

                    if (p.y < -margin) {
                        p.y = height + margin;
                        p.x = Math.random() * width;
                    } else if (p.y > height + margin) {
                        p.y = -margin;
                        p.x = Math.random() * width;
                    }

                    p.element.style.transform = `translate3d(${p.x}px, ${p.y}px, 0) rotateX(${windTiltX}deg) rotateY(${windTiltY}deg) rotateZ(${p.angle}deg) scale(${p.scale})`;
                    p.element.style.opacity = p.opacity;
                });

                animationFrameId = requestAnimationFrame(animate);
            };

            animate();

            return () => {
                if (animationFrameId) cancelAnimationFrame(animationFrameId);
                window.removeEventListener('resize', handleResize);
                window.removeEventListener('mousemove', handleMouseMove);
                window.removeEventListener('mouseleave', handleMouseLeave);
                window.removeEventListener('scroll', handleScroll);
                document.removeEventListener('visibilitychange', handleVisibilityChange);
                clearTimeout(scrollTimeout);
            };
        }
    }));

    // ── Background Canvas Flocking Animation (Services, Profile, Curation Desk) ──
    Alpine.data('canvasAmbient', () => ({
        init() {
            const canvas = this.$el;
            const ctx = canvas.getContext('2d');
            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;
            let animationFrameId = null;

            const handleResize = () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            };
            window.addEventListener('resize', handleResize);

            const particleCount = 30;
            const particles = [];
            const mouse = { x: -1000, y: -1000, active: false };

            const handleMouseMove = (e) => {
                mouse.x = e.clientX;
                mouse.y = e.clientY;
                mouse.active = true;
            };
            const handleMouseLeave = () => {
                mouse.active = false;
            };
            window.addEventListener('mousemove', handleMouseMove);
            window.addEventListener('mouseleave', handleMouseLeave);

            const getPetalColors = () => {
                const activeTheme = localStorage.getItem('nb_theme') || 'light';
                if (activeTheme === 'light' || activeTheme === 'champagne') {
                    return ['#B59A7A', '#D4AF37', '#E5C1CD', '#FFFFFF', '#D48EA1'];
                } else {
                    return ['#C5A880', '#A78BFA', '#8B5CF6', '#4B5563', '#B76E79'];
                }
            };

            for (let i = 0; i < particleCount; i++) {
                const angle = Math.random() * Math.PI * 2;
                const speed = Math.random() * 0.8 + 0.3;
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: Math.cos(angle) * speed,
                    vy: Math.sin(angle) * speed,
                    r: Math.random() * 8 + 4,
                    d: Math.random() * particleCount,
                    angle: Math.random() * 360,
                    rotationSpeed: Math.random() * 0.8 - 0.4,
                    type: Math.random() > 0.4 ? 'petal' : 'flower'
                });
            }

            function drawFlower(x, y, radius, petals, color, angle) {
                ctx.save();
                ctx.translate(x, y);
                ctx.rotate(angle * Math.PI / 180);
                ctx.fillStyle = color;
                ctx.beginPath();
                for (let i = 0; i < petals; i++) {
                    ctx.rotate(Math.PI * 2 / petals);
                    ctx.ellipse(0, -radius, radius * 0.5, radius * 0.9, 0, 0, Math.PI * 2);
                }
                ctx.fill();
                ctx.fillStyle = 'rgba(255,255,255,0.4)';
                ctx.beginPath();
                ctx.arc(0, 0, radius * 0.25, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            }

            function drawPetal(x, y, radius, color, angle) {
                ctx.save();
                ctx.translate(x, y);
                ctx.rotate(angle * Math.PI / 180);
                ctx.fillStyle = color;
                ctx.beginPath();
                ctx.moveTo(0, 0);
                ctx.quadraticCurveTo(-radius * 0.8, -radius * 1.2, 0, -radius * 2);
                ctx.quadraticCurveTo(radius * 0.8, -radius * 1.2, 0, 0);
                ctx.closePath();
                ctx.fill();
                ctx.restore();
            }

            let isVisible = true;
            const handleVisibilityChange = () => {
                isVisible = !document.hidden;
                if (isVisible && !animationFrameId) {
                    animate();
                }
            };
            document.addEventListener('visibilitychange', handleVisibilityChange);

            const animate = () => {
                if (!isVisible) {
                    animationFrameId = null;
                    return;
                }
                ctx.clearRect(0, 0, width, height);
                const colors = getPetalColors();

                particles.forEach((p, idx) => {
                    p.color = colors[idx % colors.length] + '55';

                    if (p.type === 'flower') {
                        drawFlower(p.x, p.y, p.r, 5, p.color, p.angle);
                    } else {
                        drawPetal(p.x, p.y, p.r, p.color, p.angle);
                    }

                    let flockVx = 0; let flockVy = 0;
                    let flockX = 0; let flockY = 0;
                    let avoidX = 0; let avoidY = 0;
                    let neighbors = 0; let closeNeighbors = 0;

                    const visualRange = 80;
                    const visualRangeSq = visualRange * visualRange;
                    const minDistance = 25;
                    const minDistanceSq = minDistance * minDistance;

                    particles.forEach(other => {
                        if (other === p) return;
                        let dx = other.x - p.x;
                        let dy = other.y - p.y;
                        let distSq = dx*dx + dy*dy;
                        if (distSq < visualRangeSq) {
                            flockX += other.x; flockY += other.y;
                            flockVx += other.vx; flockVy += other.vy;
                            neighbors++;
                            if (distSq < minDistanceSq) {
                                let dist = Math.sqrt(distSq) || 1;
                                avoidX -= dx / dist; avoidY -= dy / dist;
                                closeNeighbors++;
                            }
                        }
                    });

                    let ax = 0; let ay = 0;
                    if (neighbors > 0) {
                        ax += (flockX / neighbors - p.x) * 0.003;
                        ay += (flockY / neighbors - p.y) * 0.003;
                        ax += (flockVx / neighbors - p.vx) * 0.02;
                        ay += (flockVy / neighbors - p.vy) * 0.02;
                    }
                    if (closeNeighbors > 0) {
                        ax += avoidX * 0.4;
                        ay += avoidY * 0.4;
                    }

                    if (mouse.active) {
                        let mDx = p.x - mouse.x;
                        let mDy = p.y - mouse.y;
                        let mDistSq = mDx*mDx + mDy*mDy;
                        if (mDistSq < 180 * 180) {
                            let mDist = Math.sqrt(mDistSq) || 1;
                            let force = (180 - mDist) / 180;
                            ax += (mDx / mDist) * force * 1.2;
                            ay += (mDy / mDist) * force * 1.2;
                        }
                    }

                    p.vx += ax; p.vy += ay;
                    let speedSq = p.vx*p.vx + p.vy*p.vy;
                    const minSSq = 0.4 * 0.4; const maxSSq = 1.8 * 1.8;
                    if (speedSq > maxSSq) {
                        let speed = Math.sqrt(speedSq);
                        p.vx = (p.vx/speed)*1.8; p.vy = (p.vy/speed)*1.8;
                    } else if (speedSq < minSSq) {
                        if (speedSq === 0) {
                            p.vx = Math.random()*0.4; p.vy = Math.random()*0.4;
                        } else {
                            let speed = Math.sqrt(speedSq);
                            p.vx = (p.vx/speed)*0.4; p.vy = (p.vy/speed)*0.4;
                        }
                    }

                    p.x += p.vx; p.y += p.vy;
                    p.angle = Math.atan2(p.vy, p.vx) * (180 / Math.PI) + 90;

                    const margin = p.r * 2 + 10;
                    if (p.x < -margin) p.x = width + margin;
                    else if (p.x > width + margin) p.x = -margin;
                    if (p.y < -margin) p.y = height + margin;
                    else if (p.y > height + margin) p.y = -margin;
                });

                animationFrameId = requestAnimationFrame(animate);
            };

            animate();

            return () => {
                if (animationFrameId) cancelAnimationFrame(animationFrameId);
                window.removeEventListener('resize', handleResize);
                window.removeEventListener('mousemove', handleMouseMove);
                window.removeEventListener('mouseleave', handleMouseLeave);
                document.removeEventListener('visibilitychange', handleVisibilityChange);
            };
        }
    }));
});
