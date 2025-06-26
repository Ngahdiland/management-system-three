// Charts and Analytics JavaScript
class LMSCharts {
    constructor() {
        this.charts = {};
        this.colors = {
            primary: '#667eea',
            secondary: '#764ba2',
            success: '#28a745',
            danger: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8',
            light: '#f8f9fa',
            dark: '#343a40'
        };
    }

    // Initialize all charts on the page
    init() {
        this.initPerformanceChart();
        this.initAttendanceChart();
        this.initGradeDistributionChart();
        this.initCourseEnrollmentChart();
        this.initRevenueChart();
    }

    // Student Performance Chart
    initPerformanceChart() {
        const canvas = document.getElementById('performanceChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        this.charts.performance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8'],
                datasets: [{
                    label: 'Mathematics',
                    data: [85, 88, 92, 87, 90, 94, 89, 91],
                    borderColor: this.colors.primary,
                    backgroundColor: this.hexToRgba(this.colors.primary, 0.1),
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Physics',
                    data: [78, 82, 85, 80, 83, 87, 84, 86],
                    borderColor: this.colors.secondary,
                    backgroundColor: this.hexToRgba(this.colors.secondary, 0.1),
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Computer Science',
                    data: [92, 95, 88, 93, 96, 90, 94, 97],
                    borderColor: this.colors.success,
                    backgroundColor: this.hexToRgba(this.colors.success, 0.1),
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Academic Performance Over Time'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // Attendance Chart
    initAttendanceChart() {
        const canvas = document.getElementById('attendanceChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        this.charts.attendance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Late'],
                datasets: [{
                    data: [85, 10, 5],
                    backgroundColor: [
                        this.colors.success,
                        this.colors.danger,
                        this.colors.warning
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Attendance Overview'
                    }
                }
            }
        });
    }

    // Grade Distribution Chart
    initGradeDistributionChart() {
        const canvas = document.getElementById('gradeDistributionChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        this.charts.gradeDistribution = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'F'],
                datasets: [{
                    label: 'Number of Students',
                    data: [15, 25, 30, 35, 40, 30, 25, 20, 15, 10, 5],
                    backgroundColor: [
                        this.colors.success,
                        this.colors.success,
                        this.colors.success,
                        this.colors.primary,
                        this.colors.primary,
                        this.colors.primary,
                        this.colors.warning,
                        this.colors.warning,
                        this.colors.warning,
                        this.colors.danger,
                        this.colors.danger
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Grade Distribution'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    }
                }
            }
        });
    }

    // Course Enrollment Chart
    initCourseEnrollmentChart() {
        const canvas = document.getElementById('courseEnrollmentChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        this.charts.courseEnrollment = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: ['Mathematics 101', 'Physics 101', 'Computer Science', 'English Literature', 'History', 'Chemistry'],
                datasets: [{
                    label: 'Enrolled Students',
                    data: [120, 95, 150, 80, 65, 110],
                    backgroundColor: this.colors.primary,
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Course Enrollment'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    }
                }
            }
        });
    }

    // Revenue Chart
    initRevenueChart() {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        this.charts.revenue = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [45000, 52000, 48000, 55000, 60000, 58000, 65000, 70000, 68000, 75000, 80000, 85000],
                    borderColor: this.colors.success,
                    backgroundColor: this.hexToRgba(this.colors.success, 0.1),
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                }, {
                    label: 'Expenses',
                    data: [35000, 38000, 36000, 40000, 42000, 41000, 45000, 48000, 46000, 50000, 52000, 55000],
                    borderColor: this.colors.danger,
                    backgroundColor: this.hexToRgba(this.colors.danger, 0.1),
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Financial Overview'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Update chart data dynamically
    updateChart(chartName, newData) {
        if (this.charts[chartName]) {
            this.charts[chartName].data = newData;
            this.charts[chartName].update();
        }
    }

    // Load chart data from API
    async loadChartData(chartName, endpoint) {
        try {
            const response = await fetch(endpoint);
            const data = await response.json();
            
            if (data.success) {
                this.updateChart(chartName, data.data);
            }
        } catch (error) {
            console.error('Error loading chart data:', error);
        }
    }

    // Utility function to convert hex to rgba
    hexToRgba(hex, alpha) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    // Create a simple progress chart
    createProgressChart(canvasId, percentage, label) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 10;

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw background circle
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 10;
        ctx.stroke();

        // Draw progress arc
        const startAngle = -Math.PI / 2;
        const endAngle = startAngle + (percentage / 100) * 2 * Math.PI;

        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, startAngle, endAngle);
        ctx.strokeStyle = this.colors.primary;
        ctx.lineWidth = 10;
        ctx.stroke();

        // Draw percentage text
        ctx.fillStyle = '#333';
        ctx.font = 'bold 24px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(percentage + '%', centerX, centerY + 8);

        // Draw label
        ctx.fillStyle = '#666';
        ctx.font = '14px Arial';
        ctx.fillText(label, centerX, centerY + 35);
    }

    // Create a gauge chart
    createGaugeChart(canvasId, value, maxValue, label) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 20;

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw gauge background
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 15;
        ctx.stroke();

        // Calculate percentage and angle
        const percentage = (value / maxValue) * 100;
        const angle = Math.PI + (percentage / 100) * Math.PI;

        // Draw gauge value
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, Math.PI, angle);
        ctx.strokeStyle = this.getGaugeColor(percentage);
        ctx.lineWidth = 15;
        ctx.stroke();

        // Draw value text
        ctx.fillStyle = '#333';
        ctx.font = 'bold 20px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(value + '/' + maxValue, centerX, centerY + 10);

        // Draw label
        ctx.fillStyle = '#666';
        ctx.font = '14px Arial';
        ctx.fillText(label, centerX, centerY + 35);
    }

    // Get gauge color based on percentage
    getGaugeColor(percentage) {
        if (percentage >= 80) return this.colors.success;
        if (percentage >= 60) return this.colors.warning;
        return this.colors.danger;
    }
}

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const lmsCharts = new LMSCharts();
    lmsCharts.init();
    
    // Make charts available globally
    window.LMSCharts = lmsCharts;
}); 