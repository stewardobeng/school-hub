import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { StatCard } from "@/components/dashboard/StatCard";
import { EarningsChart } from "@/components/dashboard/EarningsChart";
import { EventsCalendar } from "@/components/dashboard/EventsCalendar";
import { TopPerformers } from "@/components/dashboard/TopPerformers";
import { AttendanceChart } from "@/components/dashboard/AttendanceChart";
import { CommunityCard } from "@/components/dashboard/CommunityCard";
import { Users, GraduationCap, UserCheck, DollarSign } from "lucide-react";

export default function AdminDashboard() {
  return (
    <DashboardLayout title="Dashboard" userRole="admin">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <StatCard
          icon={<Users className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="Students"
          value="12478"
        />
        <StatCard
          icon={<GraduationCap className="w-6 h-6 text-purple-600" />}
          iconBgColor="bg-purple-100"
          label="Teachers"
          value="478"
        />
        <StatCard
          icon={<UserCheck className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="Parents"
          value="8908"
        />
        <StatCard
          icon={<DollarSign className="w-6 h-6 text-amber-600" />}
          iconBgColor="bg-amber-100"
          label="Earnings"
          value="$42.8k"
        />
      </div>

      {/* Main Content Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Left Column - Chart */}
        <div className="lg:col-span-2">
          <EarningsChart />
        </div>

        {/* Right Column - Calendar */}
        <div>
          <EventsCalendar />
        </div>
      </div>

      {/* Bottom Row */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {/* Top Performers */}
        <div className="lg:col-span-1">
          <TopPerformers />
        </div>

        {/* Attendance */}
        <div>
          <AttendanceChart />
        </div>

        {/* Community Card */}
        <div>
          <CommunityCard />
        </div>
      </div>
    </DashboardLayout>
  );
}
