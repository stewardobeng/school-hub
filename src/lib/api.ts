// Use proxy path in development (configured in vite.config.ts)
// In production, use full URL or set VITE_API_URL environment variable
const API_BASE_URL = import.meta.env.VITE_API_URL || '/api';

interface ApiResponse<T> {
  success: boolean;
  data: T;
  message?: string;
  error?: string;
}

class ApiClient {
  private async request<T>(
    endpoint: string,
    options: RequestInit = {}
  ): Promise<ApiResponse<T>> {
    const url = `${API_BASE_URL}${endpoint}`;
    
    const config: RequestInit = {
      headers: {
        'Content-Type': 'application/json',
        ...options.headers,
      },
      ...options,
    };

    try {
      const response = await fetch(url, config);
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || `HTTP error! status: ${response.status}`);
      }

      return data;
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }

  // Students API
  async getStudents(params?: { search?: string; status?: string; grade?: string }) {
    const queryParams = new URLSearchParams();
    if (params?.search) queryParams.append('search', params.search);
    if (params?.status) queryParams.append('status', params.status);
    if (params?.grade) queryParams.append('grade', params.grade);
    
    const query = queryParams.toString();
    return this.request(`/students${query ? `?${query}` : ''}`);
  }

  async getStudent(id: string) {
    return this.request(`/students/${id}`);
  }

  async createStudent(data: any) {
    return this.request('/students', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async updateStudent(id: string, data: any) {
    return this.request(`/students/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    });
  }

  async deleteStudent(id: string) {
    return this.request(`/students/${id}`, {
      method: 'DELETE',
    });
  }

  // Teachers API
  async getTeachers(params?: { search?: string; status?: string; subject?: string }) {
    const queryParams = new URLSearchParams();
    if (params?.search) queryParams.append('search', params.search);
    if (params?.status) queryParams.append('status', params.status);
    if (params?.subject) queryParams.append('subject', params.subject);
    
    const query = queryParams.toString();
    return this.request(`/teachers${query ? `?${query}` : ''}`);
  }

  async getTeacher(id: string) {
    return this.request(`/teachers/${id}`);
  }

  async createTeacher(data: any) {
    return this.request('/teachers', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async updateTeacher(id: string, data: any) {
    return this.request(`/teachers/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    });
  }

  async deleteTeacher(id: string) {
    return this.request(`/teachers/${id}`, {
      method: 'DELETE',
    });
  }

  // Courses API
  async getCourses(params?: { search?: string; status?: string; grade?: string; teacher_id?: string }) {
    const queryParams = new URLSearchParams();
    if (params?.search) queryParams.append('search', params.search);
    if (params?.status) queryParams.append('status', params.status);
    if (params?.grade) queryParams.append('grade', params.grade);
    if (params?.teacher_id) queryParams.append('teacher_id', params.teacher_id);
    
    const query = queryParams.toString();
    return this.request(`/courses${query ? `?${query}` : ''}`);
  }

  async getCourse(id: string) {
    return this.request(`/courses/${id}`);
  }

  async createCourse(data: any) {
    return this.request('/courses', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async updateCourse(id: string, data: any) {
    return this.request(`/courses/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    });
  }

  async deleteCourse(id: string) {
    return this.request(`/courses/${id}`, {
      method: 'DELETE',
    });
  }

  // Exams API
  async getExams(params?: { search?: string; status?: string; type?: string; course_id?: string; grade?: string }) {
    const queryParams = new URLSearchParams();
    if (params?.search) queryParams.append('search', params.search);
    if (params?.status) queryParams.append('status', params.status);
    if (params?.type) queryParams.append('type', params.type);
    if (params?.course_id) queryParams.append('course_id', params.course_id);
    if (params?.grade) queryParams.append('grade', params.grade);
    
    const query = queryParams.toString();
    return this.request(`/exams${query ? `?${query}` : ''}`);
  }

  async getExam(id: string) {
    return this.request(`/exams/${id}`);
  }

  async createExam(data: any) {
    return this.request('/exams', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async updateExam(id: string, data: any) {
    return this.request(`/exams/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    });
  }

  async deleteExam(id: string) {
    return this.request(`/exams/${id}`, {
      method: 'DELETE',
    });
  }

  // Payments API
  async getPayments(params?: { search?: string; status?: string; type?: string; student_id?: string; start_date?: string; end_date?: string }) {
    const queryParams = new URLSearchParams();
    if (params?.search) queryParams.append('search', params.search);
    if (params?.status) queryParams.append('status', params.status);
    if (params?.type) queryParams.append('type', params.type);
    if (params?.student_id) queryParams.append('student_id', params.student_id);
    if (params?.start_date) queryParams.append('start_date', params.start_date);
    if (params?.end_date) queryParams.append('end_date', params.end_date);
    
    const query = queryParams.toString();
    return this.request(`/payments${query ? `?${query}` : ''}`);
  }

  async getPayment(id: string) {
    return this.request(`/payments/${id}`);
  }

  async createPayment(data: any) {
    return this.request('/payments', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async updatePayment(id: string, data: any) {
    return this.request(`/payments/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    });
  }

  async deletePayment(id: string) {
    return this.request(`/payments/${id}`, {
      method: 'DELETE',
    });
  }

  // Attendance API
  async getAttendance(params?: { search?: string; status?: string; teacher_id?: string; start_date?: string; end_date?: string; class_name?: string }) {
    const queryParams = new URLSearchParams();
    if (params?.search) queryParams.append('search', params.search);
    if (params?.status) queryParams.append('status', params.status);
    if (params?.teacher_id) queryParams.append('teacher_id', params.teacher_id);
    if (params?.start_date) queryParams.append('start_date', params.start_date);
    if (params?.end_date) queryParams.append('end_date', params.end_date);
    if (params?.class_name) queryParams.append('class_name', params.class_name);
    
    const query = queryParams.toString();
    return this.request(`/attendance${query ? `?${query}` : ''}`);
  }

  async getAttendanceRecord(id: string) {
    return this.request(`/attendance/${id}`);
  }

  async createAttendance(data: any) {
    return this.request('/attendance', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async updateAttendance(id: string, data: any) {
    return this.request(`/attendance/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    });
  }

  async deleteAttendance(id: string) {
    return this.request(`/attendance/${id}`, {
      method: 'DELETE',
    });
  }

  // Dashboard API
  async getDashboardStats() {
    return this.request('/dashboard/stats');
  }
}

export const api = new ApiClient();
export default api;

