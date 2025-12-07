import { useParams, useNavigate } from "react-router-dom";
import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Separator } from "@/components/ui/separator";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import {
  ArrowLeft,
  Mail,
  Phone,
  MapPin,
  Calendar,
  GraduationCap,
  BookOpen,
  Award,
  ClipboardCheck,
  CreditCard,
  User,
  FileText,
} from "lucide-react";

// Mock student data - in a real app, this would come from an API
const getStudentData = (id: string) => {
  const students: Record<string, any> = {
    ST001: {
      id: "ST001",
      name: "John Smith",
      email: "john.smith@school.edu",
      phone: "+1 234-567-8900",
      address: "123 Main Street, New York, NY 10001",
      dateOfBirth: "2008-05-15",
      grade: "Grade 10",
      enrollmentDate: "2023-09-01",
      status: "Active",
      avatar: "",
      parentName: "Jane Smith",
      parentEmail: "jane.smith@email.com",
      parentPhone: "+1 234-567-8901",
      courses: [
        { code: "MATH-101", name: "Mathematics - Algebra", teacher: "Dr. Robert Anderson", grade: "A", credits: 3 },
        { code: "ENG-101", name: "English Literature", teacher: "Ms. Jennifer Lee", grade: "B+", credits: 3 },
        { code: "SCI-201", name: "Science - Physics", teacher: "Prof. Maria Garcia", grade: "A-", credits: 4 },
        { code: "CS-301", name: "Computer Science", teacher: "Dr. Lisa Chen", grade: "A", credits: 4 },
        { code: "HIS-201", name: "World History", teacher: "Mr. James Taylor", grade: "B", credits: 3 },
      ],
      attendance: [
        { date: "2024-01-15", subject: "Mathematics", status: "Present", percentage: 95 },
        { date: "2024-01-14", subject: "English", status: "Present", percentage: 95 },
        { date: "2024-01-13", subject: "Science", status: "Absent", percentage: 90 },
        { date: "2024-01-12", subject: "Computer Science", status: "Present", percentage: 95 },
        { date: "2024-01-11", subject: "History", status: "Late", percentage: 92 },
      ],
      exams: [
        { id: "EXM001", title: "Midterm Exam - Mathematics", date: "2024-02-15", score: 92, maxScore: 100, grade: "A" },
        { id: "EXM002", title: "Quiz - English Literature", date: "2024-01-25", score: 85, maxScore: 100, grade: "B+" },
        { id: "EXM003", title: "Unit Test - Science", date: "2024-02-10", score: 88, maxScore: 100, grade: "B+" },
      ],
      payments: [
        { id: "PAY001", type: "Tuition Fee", amount: 1250.00, date: "2024-01-15", status: "Paid" },
        { id: "PAY002", type: "Library Fee", amount: 500.00, date: "2024-01-13", status: "Paid" },
        { id: "PAY003", type: "Exam Fee", amount: 300.00, date: "2024-01-11", status: "Paid" },
      ],
      assignments: [
        { id: "ASG001", title: "Algebra Homework", course: "MATH-101", dueDate: "2024-01-20", status: "Submitted", grade: "A" },
        { id: "ASG002", title: "Essay Assignment", course: "ENG-101", dueDate: "2024-01-18", status: "Submitted", grade: "B+" },
        { id: "ASG003", title: "Physics Lab Report", course: "SCI-201", dueDate: "2024-01-22", status: "Pending", grade: "-" },
      ],
    },
  };
  return students[id] || students["ST001"];
};

export default function AdminStudentDetail() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const student = getStudentData(id || "ST001");

  const overallGPA = 3.75;
  const attendanceRate = 93.4;
  const totalCredits = student.courses.reduce((sum: number, course: any) => sum + course.credits, 0);

  return (
    <DashboardLayout title="Student Details" userRole="admin">
      <div className="space-y-6">
        {/* Back Button */}
        <Button variant="outline" onClick={() => navigate("/admin/students")}>
          <ArrowLeft className="w-4 h-4 mr-2" />
          Back to Students
        </Button>

        {/* Student Profile Header */}
        <Card>
          <CardContent className="p-6">
            <div className="flex items-start gap-6">
              <Avatar className="w-32 h-32 border-4 border-primary">
                <AvatarImage src={student.avatar} alt={student.name} />
                <AvatarFallback className="text-4xl bg-primary text-primary-foreground">
                  {student.name.split(' ').map(n => n[0]).join('')}
                </AvatarFallback>
              </Avatar>
              <div className="flex-1">
                <div className="flex items-center justify-between mb-4">
                  <div>
                    <h1 className="text-3xl font-bold mb-2">{student.name}</h1>
                    <div className="flex items-center gap-4 text-muted-foreground">
                      <span className="font-mono text-sm">ID: {student.id}</span>
                      <Badge
                        variant={student.status === "Active" ? "default" : "secondary"}
                        className={student.status === "Active" ? "bg-green-500" : ""}
                      >
                        {student.status}
                      </Badge>
                    </div>
                  </div>
                  <Button>Edit Student</Button>
                </div>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div className="flex items-center gap-2">
                    <GraduationCap className="w-4 h-4 text-muted-foreground" />
                    <span className="text-sm">{student.grade}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Mail className="w-4 h-4 text-muted-foreground" />
                    <span className="text-sm">{student.email}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Phone className="w-4 h-4 text-muted-foreground" />
                    <span className="text-sm">{student.phone}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Calendar className="w-4 h-4 text-muted-foreground" />
                    <span className="text-sm">Enrolled: {student.enrollmentDate}</span>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">Overall GPA</p>
                  <p className="text-2xl font-bold">{overallGPA}</p>
                </div>
                <Award className="w-8 h-8 text-blue-600" />
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">Attendance</p>
                  <p className="text-2xl font-bold">{attendanceRate}%</p>
                </div>
                <ClipboardCheck className="w-8 h-8 text-green-600" />
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">Total Credits</p>
                  <p className="text-2xl font-bold">{totalCredits}</p>
                </div>
                <BookOpen className="w-8 h-8 text-purple-600" />
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="p-4">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">Active Courses</p>
                  <p className="text-2xl font-bold">{student.courses.length}</p>
                </div>
                <GraduationCap className="w-8 h-8 text-amber-600" />
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Detailed Information Tabs */}
        <Tabs defaultValue="overview" className="space-y-6">
          <TabsList>
            <TabsTrigger value="overview">Overview</TabsTrigger>
            <TabsTrigger value="courses">Courses</TabsTrigger>
            <TabsTrigger value="grades">Grades & Exams</TabsTrigger>
            <TabsTrigger value="attendance">Attendance</TabsTrigger>
            <TabsTrigger value="assignments">Assignments</TabsTrigger>
            <TabsTrigger value="payments">Payments</TabsTrigger>
          </TabsList>

          {/* Overview Tab */}
          <TabsContent value="overview" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Personal Information */}
              <Card>
                <CardHeader>
                  <CardTitle className="flex items-center gap-2">
                    <User className="w-5 h-5" />
                    Personal Information
                  </CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <p className="text-sm text-muted-foreground">Date of Birth</p>
                      <p className="font-medium">{student.dateOfBirth}</p>
                    </div>
                    <div>
                      <p className="text-sm text-muted-foreground">Age</p>
                      <p className="font-medium">
                        {new Date().getFullYear() - new Date(student.dateOfBirth).getFullYear()} years
                      </p>
                    </div>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground mb-2">Address</p>
                    <div className="flex items-start gap-2">
                      <MapPin className="w-4 h-4 text-muted-foreground mt-0.5" />
                      <p className="font-medium">{student.address}</p>
                    </div>
                  </div>
                </CardContent>
              </Card>

              {/* Parent/Guardian Information */}
              <Card>
                <CardHeader>
                  <CardTitle className="flex items-center gap-2">
                    <User className="w-5 h-5" />
                    Parent/Guardian Information
                  </CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Name</p>
                    <p className="font-medium">{student.parentName}</p>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground mb-2">Email</p>
                    <div className="flex items-center gap-2">
                      <Mail className="w-4 h-4 text-muted-foreground" />
                      <p className="font-medium">{student.parentEmail}</p>
                    </div>
                  </div>
                  <Separator />
                  <div>
                    <p className="text-sm text-muted-foreground mb-2">Phone</p>
                    <div className="flex items-center gap-2">
                      <Phone className="w-4 h-4 text-muted-foreground" />
                      <p className="font-medium">{student.parentPhone}</p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          {/* Courses Tab */}
          <TabsContent value="courses">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <BookOpen className="w-5 h-5" />
                  Enrolled Courses
                </CardTitle>
                <CardDescription>All courses the student is currently enrolled in</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Course Code</TableHead>
                        <TableHead>Course Name</TableHead>
                        <TableHead>Teacher</TableHead>
                        <TableHead>Credits</TableHead>
                        <TableHead>Current Grade</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {student.courses.map((course: any) => (
                        <TableRow key={course.code}>
                          <TableCell className="font-mono">
                            <Badge variant="outline">{course.code}</Badge>
                          </TableCell>
                          <TableCell className="font-medium">{course.name}</TableCell>
                          <TableCell>{course.teacher}</TableCell>
                          <TableCell>{course.credits}</TableCell>
                          <TableCell>
                            <Badge variant="secondary" className="font-semibold">
                              {course.grade}
                            </Badge>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Grades & Exams Tab */}
          <TabsContent value="grades">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Award className="w-5 h-5" />
                  Exam Results
                </CardTitle>
                <CardDescription>All exam scores and grades</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Exam</TableHead>
                        <TableHead>Date</TableHead>
                        <TableHead>Score</TableHead>
                        <TableHead>Grade</TableHead>
                        <TableHead>Status</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {student.exams.map((exam: any) => (
                        <TableRow key={exam.id}>
                          <TableCell className="font-medium">{exam.title}</TableCell>
                          <TableCell>{exam.date}</TableCell>
                          <TableCell>
                            <span className="font-semibold">
                              {exam.score}/{exam.maxScore}
                            </span>
                          </TableCell>
                          <TableCell>
                            <Badge
                              variant={exam.grade.startsWith("A") ? "default" : "secondary"}
                              className={exam.grade.startsWith("A") ? "bg-green-500" : ""}
                            >
                              {exam.grade}
                            </Badge>
                          </TableCell>
                          <TableCell>
                            <Badge variant="outline">Completed</Badge>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Attendance Tab */}
          <TabsContent value="attendance">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <ClipboardCheck className="w-5 h-5" />
                  Attendance Record
                </CardTitle>
                <CardDescription>Recent attendance history</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Date</TableHead>
                        <TableHead>Subject</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Attendance Rate</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {student.attendance.map((record: any, index: number) => (
                        <TableRow key={index}>
                          <TableCell className="font-medium">{record.date}</TableCell>
                          <TableCell>{record.subject}</TableCell>
                          <TableCell>
                            <Badge
                              variant={
                                record.status === "Present"
                                  ? "default"
                                  : record.status === "Late"
                                  ? "secondary"
                                  : "destructive"
                              }
                              className={
                                record.status === "Present"
                                  ? "bg-green-500"
                                  : record.status === "Late"
                                  ? "bg-amber-500"
                                  : "bg-red-500"
                              }
                            >
                              {record.status}
                            </Badge>
                          </TableCell>
                          <TableCell>{record.percentage}%</TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Assignments Tab */}
          <TabsContent value="assignments">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <FileText className="w-5 h-5" />
                  Assignments
                </CardTitle>
                <CardDescription>All assignments and their status</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Assignment</TableHead>
                        <TableHead>Course</TableHead>
                        <TableHead>Due Date</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Grade</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {student.assignments.map((assignment: any) => (
                        <TableRow key={assignment.id}>
                          <TableCell className="font-medium">{assignment.title}</TableCell>
                          <TableCell>
                            <Badge variant="outline" className="font-mono">
                              {assignment.course}
                            </Badge>
                          </TableCell>
                          <TableCell>{assignment.dueDate}</TableCell>
                          <TableCell>
                            <Badge
                              variant={assignment.status === "Submitted" ? "default" : "secondary"}
                              className={assignment.status === "Submitted" ? "bg-green-500" : ""}
                            >
                              {assignment.status}
                            </Badge>
                          </TableCell>
                          <TableCell>
                            {assignment.grade !== "-" ? (
                              <Badge variant="secondary" className="font-semibold">
                                {assignment.grade}
                              </Badge>
                            ) : (
                              <span className="text-muted-foreground">-</span>
                            )}
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>

          {/* Payments Tab */}
          <TabsContent value="payments">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <CreditCard className="w-5 h-5" />
                  Payment History
                </CardTitle>
                <CardDescription>All payment transactions</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Payment ID</TableHead>
                        <TableHead>Type</TableHead>
                        <TableHead>Amount</TableHead>
                        <TableHead>Date</TableHead>
                        <TableHead>Status</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {student.payments.map((payment: any) => (
                        <TableRow key={payment.id}>
                          <TableCell className="font-mono text-sm">{payment.id}</TableCell>
                          <TableCell>
                            <Badge variant="outline">{payment.type}</Badge>
                          </TableCell>
                          <TableCell className="font-semibold text-green-600">
                            ${payment.amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                          </TableCell>
                          <TableCell>{payment.date}</TableCell>
                          <TableCell>
                            <Badge variant="default" className="bg-green-500">
                              {payment.status}
                            </Badge>
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </DashboardLayout>
  );
}

