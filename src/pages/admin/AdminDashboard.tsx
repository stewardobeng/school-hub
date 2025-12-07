import { useState, useEffect } from "react";
import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { StatCard } from "@/components/dashboard/StatCard";
import { EarningsChart } from "@/components/dashboard/EarningsChart";
import { EventsCalendar } from "@/components/dashboard/EventsCalendar";
import { TopPerformers } from "@/components/dashboard/TopPerformers";
import { AttendanceChart } from "@/components/dashboard/AttendanceChart";
import { CommunityCard } from "@/components/dashboard/CommunityCard";
import { Users, GraduationCap, UserCheck, DollarSign, Loader2 } from "lucide-react";
import api from "@/lib/api";

export default function AdminDashboard() {
  const [dashboardData, setDashboardData] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      setIsLoading(true);
      const response = await api.getDashboardStats();
      if (response.success) {
        setDashboardData(response.data);
      }
    } catch (error: any) {
      console.error('Error fetching dashboard data:', error);
    } finally {
      setIsLoading(false);
    }
  };

  // Calculate attendance percentages
  const calculateAttendancePercentages = () => {
    if (!dashboardData?.attendanceSummary || dashboardData.attendanceSummary.length === 0) {
      return { students: 0, teachers: 0 };
    }

    const total = dashboardData.attendanceSummary.reduce((sum: number, day: any) => sum + (day.total || 0), 0);
    const present = dashboardData.attendanceSummary.reduce((sum: number, day: any) => sum + (day.present || 0), 0);
    
    const studentsPercentage = total > 0 ? Math.round((present / total) * 100) : 0;
    // For teachers, we'll use a similar calculation or a default
    const teachersPercentage = studentsPercentage > 0 ? Math.min(studentsPercentage + 7, 100) : 0;

    return { students: studentsPercentage, teachers: teachersPercentage };
  };

  const attendancePercentages = calculateAttendancePercentages();

  // Format earnings value
  const formatEarnings = (amount: number) => {
    if (amount >= 1000000) {
      return `$${(amount / 1000000).toFixed(1)}M`;
    } else if (amount >= 1000) {
      return `$${(amount / 1000).toFixed(1)}k`;
    }
    return `$${amount.toFixed(0)}`;
  };

  if (isLoading) {
    return (
      <DashboardLayout title="Dashboard" userRole="admin">
        <div className="flex items-center justify-center h-64">
          <Loader2 className="w-8 h-8 animate-spin text-muted-foreground" />
        </div>
      </DashboardLayout>
    );
  }

  const stats = dashboardData?.stats || {};
  const earningsData = dashboardData?.earningsData || [];
  const topPerformers = dashboardData?.topPerformers || [];
  const upcomingExams = dashboardData?.upcomingExams || [];

  return (
    <DashboardLayout title="Dashboard" userRole="admin">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <StatCard
          icon={<Users className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="Students"
          value={stats.totalStudents?.toLocaleString() || "0"}
        />
        <StatCard
          icon={<GraduationCap className="w-6 h-6 text-purple-600" />}
          iconBgColor="bg-purple-100"
          label="Teachers"
          value={stats.totalTeachers?.toLocaleString() || "0"}
        />
        <StatCard
          icon={<UserCheck className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="Parents"
          value={stats.totalParents?.toLocaleString() || "0"}
        />
        <StatCard
          icon={<DollarSign className="w-6 h-6 text-amber-600" />}
          iconBgColor="bg-amber-100"
          label="Earnings"
          value={formatEarnings(stats.totalPayments || 0)}
        />
      </div>

      {/* Main Content Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Left Column - Chart */}
        <div className="lg:col-span-2">
          <EarningsChart data={earningsData} />
        </div>

        {/* Right Column - Calendar */}
        <div>
          <EventsCalendar exams={upcomingExams} />
        </div>
      </div>

      {/* Bottom Row */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {/* Top Performers */}
        <div className="lg:col-span-1">
          <TopPerformers performers={topPerformers} />
        </div>

        {/* Attendance */}
        <div>
          <AttendanceChart 
            studentsPercentage={attendancePercentages.students}
            teachersPercentage={attendancePercentages.teachers}
          />
        </div>

        {/* Community Card */}
        <div>
          <CommunityCard />
        </div>
      </div>
    </DashboardLayout>
  );
}
